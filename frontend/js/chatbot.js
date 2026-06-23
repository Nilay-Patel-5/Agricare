(function () {
    const messagesEl = document.getElementById('chatMessages');
    const inputEl = document.getElementById('chatInput');
    const formEl = document.getElementById('chatForm');
    const sendBtn = document.getElementById('sendBtn');
    const errorEl = document.getElementById('chatError');
    const profileSummaryEl = document.getElementById('profileSummary');
    const clearChatBtn = document.getElementById('clearChatBtn');
    const promptButtons = document.querySelectorAll('.prompt-btn');

    const sessionHistoryList = document.getElementById('sessionHistoryList');
    const newChatBtn = document.getElementById('newChatBtn');

    const micBtn = document.getElementById('micBtn');
    const imgBtn = document.getElementById('imgBtn');
    const imageInput = document.getElementById('imageInput');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const removeImgBtn = document.getElementById('removeImgBtn');

    const apiUrl = '/backend/chat_api.php';
    const shopsApiUrl = '/backend/shops_api.php';

    function getBrowserLocation() {
        return new Promise((resolve) => {
            if (!navigator.geolocation) {
                resolve(null);
                return;
            }
            navigator.geolocation.getCurrentPosition(
                (pos) => resolve({ lat: pos.coords.latitude, lng: pos.coords.longitude }),
                () => resolve(null),
                { timeout: 8000, maximumAge: 60000 }
            );
        });
    }

    async function showNearbyShops(pest = '') {
        stopSpeaking();

        const user = getUserPayload();

        if (messagesEl.children.length === 0 || messagesEl.textContent.includes('Start a farming conversation')) {
            messagesEl.innerHTML = '';
        }
        renderMessage('user', pest ? `${(window.CHAT_T && window.CHAT_T.findShopsMsg && pest) ? 'Show shops for: ' + pest : 'Show shops for: ' + pest}` : ((window.CHAT_T && window.CHAT_T.findShopsMsg) || 'Find nearby agricultural shops'));

        const loadingEl = renderMessage('assistant', (window.CHAT_T && window.CHAT_T.gettingLocation) || '<i class="fas fa-map-marker-alt text-emerald-600 animate-pulse"></i> Getting your location & finding nearby shops...');

        try {
            const gps = await getBrowserLocation();

            const params = new URLSearchParams({ district: user.district || '' });
            if (pest) params.set('pest', pest);
            if (gps) {
                params.set('lat', gps.lat);
                params.set('lng', gps.lng);
            }

            const res = await fetch(`${shopsApiUrl}?${params.toString()}`);
            const data = await res.json();
            loadingEl.remove();

            renderShopResult({
                pest_name: pest || 'Agricultural Shops Near You',
                pesticides: data.pesticides || [],
                shops: data.shops || [],
                map_embed_src: data.map_embed_src || null,
                gps
            });
        } catch (e) {
            loadingEl.remove();
            renderMessage('assistant', 'Could not fetch shop data. Please try again.');
        }
    }

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    let recognition = null;
    let isListening = false;
    let recognitionStopRequested = false;
    let isSpeaking = false;
    let currentUtterance = null;
    let recognitionTranscript = '';
    const defaultPlaceholder = inputEl ? (inputEl.getAttribute('placeholder') || 'Ask about your crop, prices, or subsidies...') : '';

    function updateTextareaHeight(resetToMin) {
        if (!inputEl) return;
        if (resetToMin) {
            inputEl.style.height = 'auto';
            return;
        }
        inputEl.style.height = 'auto';
        const maxHeight = 180;
        inputEl.style.height = Math.min(inputEl.scrollHeight, maxHeight) + 'px';
        inputEl.style.overflowY = inputEl.scrollHeight > maxHeight ? 'auto' : 'hidden';
    }

    function updateMicUI() {
        if (!micBtn) return;
        if (isListening) {
            micBtn.classList.remove('bg-slate-100', 'text-slate-600');
            micBtn.classList.add('bg-red-500', 'text-white', 'animate-pulse');
            micBtn.innerHTML = '<i class="fas fa-stop"></i>';
            inputEl.placeholder = 'Listening... Speak now';
        } else {
            micBtn.classList.remove('bg-red-500', 'text-white', 'animate-pulse');
            micBtn.classList.add('bg-slate-100', 'text-slate-600');
            micBtn.innerHTML = '<i class="fas fa-microphone"></i>';
            inputEl.placeholder = defaultPlaceholder;
        }
    }

    function updateVoiceButtonsUI() {
        const btnDesktop = document.getElementById('muteBtnDesktop');
        const btnMobile = document.getElementById('muteBtnMobile');
        const btnInput = document.getElementById('muteBtnInput');

        if (isSpeaking) {
            if (btnDesktop) btnDesktop.innerHTML = 'Voice <span>Stop</span>';
            if (btnMobile) btnMobile.innerHTML = '<i class="fas fa-stop"></i>';
            if (btnInput) btnInput.innerHTML = '<i class="fas fa-stop text-lg"></i>';
            return;
        }

        const iconClass = isMuted ? 'fa-volume-mute' : 'fa-volume-up';
        const labelText = isMuted ? 'Off' : 'On';

        if (btnDesktop) btnDesktop.innerHTML = `<i class="fas ${iconClass}"></i> Voice <span>${labelText}</span>`;
        if (btnMobile) btnMobile.innerHTML = `<i class="fas ${iconClass}"></i>`;
        if (btnInput) btnInput.innerHTML = `<i class="fas ${iconClass} text-lg"></i>`;
    }

    function stopSpeaking() {
        if (!window.speechSynthesis) return;
        currentUtterance = null;
        isSpeaking = false;
        window.speechSynthesis.cancel();
        updateVoiceButtonsUI();
    }

    function stopRecognition(clearListeningState) {
        if (!recognition || !isListening) {
            if (clearListeningState) {
                recognitionStopRequested = false;
                isListening = false;
                updateMicUI();
            }
            return;
        }

        recognitionStopRequested = true;
        try {
            recognition.abort();
        } catch (err) {
            try {
                recognition.stop();
            } catch (e) {}
        }

        if (clearListeningState) {
            isListening = false;
            updateMicUI();
        }
    }

    function resetComposer() {
        if (!inputEl) return;
        inputEl.value = '';
        updateTextareaHeight(true);
        updateTextareaHeight();
    }

    if (SpeechRecognition) {
        recognition = new SpeechRecognition();
        recognition.continuous = false;
        recognition.interimResults = true;

        recognition.onstart = () => {
            recognitionStopRequested = false;
            recognitionTranscript = inputEl.value || '';
            isListening = true;
            updateMicUI();
        };

        recognition.onend = () => {
            isListening = false;
            recognitionStopRequested = false;
            updateMicUI();
        };

        recognition.onresult = (event) => {
            let transcript = '';
            for (let i = event.resultIndex; i < event.results.length; ++i) {
                transcript += event.results[i][0].transcript;
            }
            inputEl.value = transcript.trimStart();
            updateTextareaHeight();
        };

        recognition.onerror = (event) => {
            if (event.error !== 'no-speech' && event.error !== 'aborted') {
                console.error('Speech recognition error:', event.error);
                setError('Voice input failed: ' + event.error);
            }
        };
    }

    let isMuted = localStorage.getItem('agricare_muted') === 'true';

    function updateMuteUI() {
        updateVoiceButtonsUI();
    }

    function toggleMute() {
        if (isSpeaking) {
            stopSpeaking();
            return;
        }

        isMuted = !isMuted;
        localStorage.setItem('agricare_muted', isMuted ? 'true' : 'false');

        if (isMuted) stopSpeaking();
        updateMuteUI();
    }

    // Cache loaded voices and resolve once ready
    let _voicesCache = null;
    function getVoicesReady() {
        return new Promise((resolve) => {
            const voices = window.speechSynthesis.getVoices();
            if (voices.length > 0) {
                _voicesCache = voices;
                return resolve(voices);
            }
            // Browsers load voices async; wait for event
            window.speechSynthesis.onvoiceschanged = () => {
                _voicesCache = window.speechSynthesis.getVoices();
                resolve(_voicesCache);
            };
            // Fallback timeout in case event never fires
            setTimeout(() => resolve(window.speechSynthesis.getVoices() || []), 1500);
        });
    }

    // Priority-ranked voice picker:
    // 1. Exact locale match (e.g. gu-IN)  2. Language prefix match (e.g. gu)  3. null → browser default
    function pickVoice(voices, bcp47) {
        const lang = bcp47.toLowerCase();
        const prefix = lang.split('-')[0];

        // 1 — exact locale, prefer non-remote
        const exactLocal = voices.find(v => v.lang.toLowerCase() === lang && !v.remote);
        if (exactLocal) return exactLocal;

        // 2 — exact locale, allow remote
        const exactRemote = voices.find(v => v.lang.toLowerCase() === lang);
        if (exactRemote) return exactRemote;

        // 3 — same language prefix, prefer non-remote
        const prefixLocal = voices.find(v => v.lang.toLowerCase().startsWith(prefix) && !v.remote);
        if (prefixLocal) return prefixLocal;

        // 4 — same language prefix, any
        const prefixAny = voices.find(v => v.lang.toLowerCase().startsWith(prefix));
        if (prefixAny) return prefixAny;

        return null; // let browser choose
    }

    async function speak(text) {
        if (!window.speechSynthesis || isMuted || !text) return;

        stopSpeaking();

        const utterance = new SpeechSynthesisUtterance(text);
        currentUtterance = utterance;

        // Determine language: prefer the globally set CHAT_LANG (from chatbot.php),
        // then fall back to the user's stored pref_lang
        const activeLang = window.CHAT_LANG || getUserPayload().pref_lang || 'en';
        const langMap = { en: 'en-IN', hi: 'hi-IN', gu: 'gu-IN' };
        const bcp47 = langMap[activeLang] || 'en-IN';
        utterance.lang = bcp47;

        // Rate & pitch adjustments per language for naturalness
        if (activeLang === 'hi') {
            utterance.rate = 0.9;
            utterance.pitch = 1.05;
        } else if (activeLang === 'gu') {
            utterance.rate = 0.88;
            utterance.pitch = 1.0;
        } else {
            utterance.rate = 1.0;
            utterance.pitch = 1.0;
        }

        // Load voices (handles async browser loading)
        const voices = await getVoicesReady();
        const voice = pickVoice(voices, bcp47);
        if (voice) utterance.voice = voice;

        utterance.onstart = () => {
            isSpeaking = true;
            updateVoiceButtonsUI();
        };

        utterance.onend = () => {
            if (currentUtterance === utterance) {
                currentUtterance = null;
                isSpeaking = false;
                updateVoiceButtonsUI();
            }
        };

        utterance.onerror = () => {
            if (currentUtterance === utterance) {
                currentUtterance = null;
                isSpeaking = false;
                updateVoiceButtonsUI();
            }
        };

        window.speechSynthesis.speak(utterance);
    }

    function getStoredUser() {
        try {
            return JSON.parse(sessionStorage.getItem('agricare_user') || localStorage.getItem('agricare_user') || 'null') || {};
        } catch (err) {
            return {};
        }
    }

    function getSessionKey() {
        const existing = localStorage.getItem('agricare_chat_session');
        if (existing) return existing;

        const created = 'chat_' + Math.random().toString(36).slice(2) + Date.now().toString(36);
        localStorage.setItem('agricare_chat_session', created);
        return created;
    }

    function getUserPayload() {
        const user = getStoredUser();
        return {
            id: user.id || null,
            name: user.name || '',
            role: user.role || 'farmer',
            pref_lang: user.pref_lang || localStorage.getItem('agricare_lang') || 'en',
            district: user.district || '',
            city: user.city || '',
            crop: localStorage.getItem('agri_crop') || ''
        };
    }

    function escapeHtml(text) {
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function nl2br(text) {
        return escapeHtml(text).replace(/\n/g, '<br>');
    }

    function parseMarkdown(text) {
        if (!text) return '';
        let html = escapeHtml(text);
        
        // Bold: **text** -> <strong>text</strong>
        html = html.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold">$1</strong>');
        
        // Italic: *text* -> <em>text</em>
        html = html.replace(/(?:^|[^\*])\*([^\*]+)\*(?=[^\*]|$)/g, function(match, p1) {
            return match.replace('*' + p1 + '*', '<em>' + p1 + '</em>');
        });
        
        // Bullet points: * or - at start of line
        html = html.replace(/(?:^|\n)(\s*)[\*\-]\s+(.*)/g, '\n$1• $2');
        
        return html.replace(/\n/g, '<br>');
    }

    function setError(message) {
        if (!message) {
            errorEl.classList.add('hidden');
            errorEl.textContent = '';
            return;
        }
        errorEl.classList.remove('hidden');
        errorEl.textContent = message;
    }

    function renderProfileSummary() {
        const user = getUserPayload();
        const name = user.name || 'Guest farmer';
        const district = user.district || 'district not set';
        const crop = user.crop || 'crop not selected';
        profileSummaryEl.textContent = `${name} • ${district} • ${crop}`;
    }

    function renderMessage(role, text) {
        const isUser = role === 'user';
        const wrapper = document.createElement('div');

        if (isUser) {
            wrapper.className = 'w-full px-4 mb-6 flex justify-end';
            wrapper.innerHTML = `
                <div class="max-w-3xl w-full flex justify-end">
                    <div class="bg-emerald-600 text-white px-5 py-3 rounded-2xl max-w-[80%] md:max-w-[70%] text-[15px] leading-relaxed break-words whitespace-pre-wrap shadow-sm">
                        ${parseMarkdown(text)}
                    </div>
                </div>
            `;
        } else {
            wrapper.className = 'w-full px-4 mb-6 flex justify-center hover:bg-gray-50 py-2 transition-colors rounded-xl';
            wrapper.innerHTML = `
                <div class="max-w-3xl w-full flex gap-4">
                    <div class="w-8 h-8 shrink-0 rounded-full flex items-center justify-center bg-emerald-600 text-white text-sm shadow-sm">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="flex-1 text-gray-800 text-[15px] leading-relaxed break-words whitespace-pre-wrap py-1 prose">
                        ${parseMarkdown(text)}
                    </div>
                </div>
            `;
        }

        messagesEl.appendChild(wrapper);
        messagesEl.scrollTop = messagesEl.scrollHeight;
        return wrapper;
    }

    function renderEmptyState() {
        const t = window.CHAT_T || {};
        messagesEl.innerHTML = `
            <div class="flex flex-col items-center justify-center h-full px-4 text-center pb-20">
                <div class="w-16 h-16 bg-emerald-600 text-white rounded-full flex items-center justify-center text-3xl mb-6 shadow-lg shadow-emerald-200">
                    <i class="fas fa-leaf"></i>
                </div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">${t.welcomeTitle || 'How can I help you farm today?'}</h2>
                <p class="text-gray-400 text-sm max-w-sm">${t.welcomeSubtitle || 'Ask about subsidies, crop market prices, daily tasks, or upload a photo of a pest for AI identification.'}</p>
            </div>
        `;
    }

    function renderPestResult(data) {
        const wrapper = document.createElement('div');
        wrapper.className = 'w-full px-4 mb-6 flex justify-center';

        const t = window.CHAT_T || {};
        let pestHtml = `
            <div class="max-w-3xl w-full flex gap-4">
                <div class="w-8 h-8 shrink-0 rounded-full flex items-center justify-center bg-emerald-600 text-white text-sm shadow-sm">
                    <i class="fas fa-leaf"></i>
                </div>
                <div class="flex-1 w-full max-w-2xl rounded-2xl overflow-hidden border border-gray-200 shadow-md bg-white text-gray-800 mt-1">

                <div class="bg-gradient-to-r from-orange-500 to-red-500 px-5 py-4 text-white">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-bug text-2xl"></i>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-80">${t.pestDetected || 'Pest / Disease Detected'}</p>
                            <p class="text-xl font-black">${escapeHtml(data.pest_name)}</p>
                        </div>
                    </div>
                </div>
        `;

        if (data.pesticides && data.pesticides.length > 0) {
            pestHtml += `<div class="px-5 py-4 border-b border-gray-100"><p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3">${t.recommendedPesticides || '<i class="fas fa-flask text-emerald-600"></i> Recommended Pesticides'}</p><div class="space-y-2">`;
            data.pesticides.forEach((p) => {
                pestHtml += `<div class="flex items-start justify-between gap-3 rounded-xl bg-gray-50 border border-gray-200 px-4 py-3">
                    <div><p class="font-black text-gray-800 text-sm">${escapeHtml(p.name || '')}</p>
                    <p class="text-xs text-gray-500">${escapeHtml(p.brand || '')} ${p.usage_instructions ? '• ' + escapeHtml(p.usage_instructions) : ''}</p></div>
                    <span class="shrink-0 text-xs font-black text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-1 rounded-lg whitespace-nowrap">₹ ${escapeHtml(p.price_range || 'N/A')}</span>
                </div>`;
            });
            pestHtml += `</div></div>`;
        } else {
            pestHtml += `<div class="px-5 py-3 border-b border-gray-100 text-xs text-gray-400">${t.noPestMatch || 'No specific matches found in your local regional database.'}</div>`;
        }

        if (data.shops && data.shops.length > 0) {
            pestHtml += `<div class="px-5 py-4"><p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3">${t.nearbyShops || '<i class="fas fa-store text-emerald-600"></i> Nearby Agricultural Shops'}</p><div class="space-y-3">`;
            data.shops.forEach((s) => {
                pestHtml += buildShopCard(s);
            });
            pestHtml += `</div></div>`;
        } else {
            pestHtml += `<div class="px-5 py-3 text-xs text-gray-400">${t.noShopsFound || 'No nearby shops found in your current district profile.'}</div>`;
        }

        pestHtml += `</div></div></div>`;
        wrapper.innerHTML = pestHtml;
        messagesEl.appendChild(wrapper);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function buildShopCard(s) {
        const dirBtn = s.directions_url
            ? `<a href="${escapeHtml(s.directions_url)}" target="_blank" rel="noopener"
                  class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black px-3 py-2 rounded-xl transition-colors shadow-sm">
                  <i class="fas fa-route"></i> Directions
               </a>`
            : '';
        const mapBtn = s.map_url
            ? `<a href="${escapeHtml(s.map_url)}" target="_blank" rel="noopener"
                  class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black px-3 py-2 rounded-xl transition-colors shadow-sm">
                  <i class="fas fa-map-marker-alt"></i> Open Maps
               </a>`
            : '';
        return `
            <div class="rounded-xl border border-gray-200 p-4 bg-gray-50 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 shrink-0">
                        <i class="fas fa-store text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-800 text-sm truncate">${escapeHtml(s.name || '')}</p>
                        <p class="text-xs text-gray-500 mt-0.5"><i class='fas fa-map-marker-alt text-emerald-600'></i> ${escapeHtml((s.address || '') + (s.city ? ', ' + s.city : ''))}</p>
                        ${s.phone ? `<p class="text-xs text-gray-500 mt-0.5"><i class='fas fa-phone text-emerald-600'></i> ${escapeHtml(s.phone)}</p>` : ''}
                    </div>
                </div>
                <div class="flex gap-2 mt-3 flex-wrap">${mapBtn}${dirBtn}</div>
            </div>`;
    }

    function renderShopResult(data) {
        const wrapper = document.createElement('div');
        wrapper.className = 'w-full px-4 mb-6 flex justify-center';
        const t = window.CHAT_T || {};

        const gpsNote = data.gps
            ? `<span class="text-[10px] text-gray-300 font-medium ml-2 bg-black/30 px-2 py-0.5 rounded-full"><i class='fas fa-map-marker-alt text-emerald-600'></i> GPS: ${data.gps.lat.toFixed(4)}, ${data.gps.lng.toFixed(4)}</span>`
            : `<span class="text-[10px] text-gray-300 font-medium ml-2 bg-black/30 px-2 py-0.5 rounded-full"><i class='fas fa-map-marker-alt text-emerald-600'></i> Based on district</span>`;

        let html = `
            <div class="max-w-3xl w-full flex gap-4">
                <div class="w-8 h-8 shrink-0 rounded-full flex items-center justify-center bg-emerald-600 text-white text-sm shadow-sm">
                    <i class="fas fa-leaf"></i>
                </div>
                <div class="flex-1 w-full max-w-2xl rounded-2xl overflow-hidden border border-gray-200 shadow-md bg-white text-gray-800 mt-1">
                <div class="bg-gradient-to-r from-blue-600 to-emerald-600 px-5 py-4 text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center">
                            <i class="fas fa-map-marked-alt text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-80">${t.nearbyShopsTitle || 'Nearby Shops'}</p>
                            <p class="text-lg font-black">${escapeHtml(data.pest_name)}</p>
                            ${gpsNote}
                        </div>
                    </div>
                </div>
        `;

        if (data.map_embed_src) {
            html += `
                <div class="w-full" style="height:260px;">
                    <iframe
                        src="${escapeHtml(data.map_embed_src)}"
                        width="100%"
                        height="260"
                        style="border:0;display:block;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Nearby agricultural shops map">
                    </iframe>
                </div>
            `;
        }

        if (data.shops && data.shops.length > 0) {
            html += `<div class="px-5 py-4"><p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3"><i class="fas fa-store text-emerald-600"></i> Shops in Database Near You</p><div class="space-y-3">`;
            data.shops.forEach((s) => {
                html += buildShopCard(s);
            });
            html += `</div>`;

            const user = getUserPayload();
            const q = encodeURIComponent('agricultural shop near ' + (user.district || 'me'));
            const moreUrl = data.gps
                ? `https://www.google.com/maps/search/agricultural+shop/@${data.gps.lat},${data.gps.lng},14z`
                : `https://www.google.com/maps/search/?api=1&query=${q}`;
            html += `
                <div class="pt-3 border-t border-slate-100 mt-3">
                    <a href="${moreUrl}" target="_blank" rel="noopener"
                       class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-emerald-600 text-white text-xs font-black shadow-md hover:opacity-90 transition-opacity">
                        <i class="fas fa-search-location"></i> ${(window.CHAT_T && window.CHAT_T.searchMoreShops) || 'Search More Shops on Google Maps'}
                    </a>
                </div>
            `;
            html += `</div>`;
        } else {
            html += `<div class="px-5 py-4 text-sm text-slate-500">No shops found in the database for your district. Use the button below to search on Google Maps directly.</div>`;
            const user = getUserPayload();
            const moreUrl = data.gps
                ? `https://www.google.com/maps/search/agricultural+shop/@${data.gps.lat},${data.gps.lng},14z`
                : `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent('agricultural shop near ' + (user.district || 'me'))}`;
            html += `<div class="px-5 pb-5"><a href="${moreUrl}" target="_blank" rel="noopener" class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-emerald-600 text-white text-xs font-black shadow-md"><i class="fas fa-map"></i> Open Google Maps</a></div>`;
        }

        html += `</div></div></div>`;
        wrapper.innerHTML = html;
        messagesEl.appendChild(wrapper);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    async function loadSessions() {
        const user = getUserPayload();
        if (!user.id) return;

        const response = await fetch(`${apiUrl}?sessions=1&user_id=${user.id}`);
        const data = await response.json();
        const currentSession = getSessionKey();

        sessionHistoryList.innerHTML = '';

        if (!data.sessions || data.sessions.length === 0) {
            sessionHistoryList.innerHTML = '<div class="p-8 text-center opacity-40"><i class="fas fa-history text-2xl mb-2"></i><p class="text-[10px] font-bold uppercase tracking-widest">' + ((window.CHAT_T && window.CHAT_T.noHistoryYet) || 'No history yet') + '</p></div>';
            return;
        }

        const groups = {
            Today: [],
            Yesterday: [],
            'Previous 7 Days': [],
            Older: []
        };

        const now = new Date();
        const todayStr = now.toDateString();
        const yesterday = new Date(now);
        yesterday.setDate(now.getDate() - 1);
        const yesterdayStr = yesterday.toDateString();
        const sevenDaysAgo = new Date(now);
        sevenDaysAgo.setDate(now.getDate() - 7);

        data.sessions.forEach((s) => {
            const d = new Date(s.last_activity);
            const dateStr = d.toDateString();

            if (dateStr === todayStr) groups.Today.push(s);
            else if (dateStr === yesterdayStr) groups.Yesterday.push(s);
            else if (d > sevenDaysAgo) groups['Previous 7 Days'].push(s);
            else groups.Older.push(s);
        });

        Object.keys(groups).forEach((groupName) => {
            if (groups[groupName].length === 0) return;

            const groupDiv = document.createElement('div');
            groupDiv.className = 'history-group-title';
            groupDiv.textContent = groupName;
            sessionHistoryList.appendChild(groupDiv);

            groups[groupName].forEach((s) => {
                const isActive = s.session_key === currentSession;
                const item = document.createElement('div');
                item.className = 'group w-full relative';

                let title = s.title || 'New Conversation';
                if (title.length > 25) title = title.substring(0, 22) + '...';

                item.innerHTML = `
                    <div class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm mb-1 text-left ${isActive ? 'active' : ''} pr-8 cursor-pointer relative">
                        <span class="truncate flex-1">${escapeHtml(title)}</span>
                        <button class="delete-session-btn absolute right-2 text-gray-500 hover:text-red-400 transition-colors opacity-0 group-hover:opacity-100" data-key="${s.session_key}" title="Delete chat">
                            <i class="fas fa-trash-alt text-[11px]"></i>
                        </button>
                    </div>
                `;

                item.querySelector('.nav-item').addEventListener('click', () => switchSession(s.session_key));

                const delBtn = item.querySelector('.delete-session-btn');
                delBtn.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    if (!confirm('Are you sure you want to delete this conversation?')) return;

                    const userId = getUserPayload().id;
                    const res = await fetch(`${apiUrl}?user_id=${userId}&session_key=${s.session_key}`, {
                        method: 'DELETE'
                    });

                    if (res.ok) {
                        if (s.session_key === getSessionKey()) {
                            startNewChat();
                        } else {
                            loadSessions();
                        }
                    }
                });

                sessionHistoryList.appendChild(item);
            });
        });
    }

    async function switchSession(key) {
        stopSpeaking();
        stopRecognition(true);
        localStorage.setItem('agricare_chat_session', key);
        resetComposer();
        clearImage();
        await loadHistory();
        loadSessions();
    }

    async function loadHistory() {
        const user = getUserPayload();
        const params = new URLSearchParams({
            session_key: getSessionKey()
        });
        if (user.id) params.set('user_id', String(user.id));

        const response = await fetch(`${apiUrl}?${params.toString()}`);
        const text = await response.text();
        let data;

        try {
            data = JSON.parse(text);
        } catch (err) {
            console.error('Raw history response:', text);
            throw new Error('Chat history endpoint returned invalid JSON.');
        }

        messagesEl.innerHTML = '';

        if (!Array.isArray(data.messages) || data.messages.length === 0) {
            renderEmptyState();
            return;
        }

        data.messages.forEach((msg) => {
            renderMessage(msg.role === 'assistant' ? 'assistant' : 'user', msg.message || '');
        });

        loadSessions();
    }

    async function sendMessage(text) {
        stopSpeaking();
        stopRecognition(true);

        const trimmed = String(text || '').trim();
        const imageFile = imageInput.files[0];

        if (!trimmed && !imageFile) return;

        setError('');
        if (messagesEl.children.length === 1 && messagesEl.textContent.includes('Start a farming conversation')) {
            messagesEl.innerHTML = '';
        }

        const userMsg = imageFile ? `[Sent a photo] ${trimmed}` : trimmed;
        renderMessage('user', userMsg);

        resetComposer();
        clearImage();

        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i>';

        const pendingEl = renderMessage('assistant', (window.CHAT_T && window.CHAT_T.thinking) || 'Thinking...');

        try {
            const formData = new FormData();
            formData.append('message', trimmed);
            formData.append('session_key', getSessionKey());
            formData.append('user[id]', getUserPayload().id || '');
            formData.append('user[name]', getUserPayload().name || '');
            formData.append('user[role]', getUserPayload().role || 'farmer');
            formData.append('user[pref_lang]', getUserPayload().pref_lang || 'en');
            formData.append('user[district]', getUserPayload().district || '');
            formData.append('user[city]', getUserPayload().city || '');
            formData.append('user[crop]', getUserPayload().crop || '');

            if (imageFile) {
                formData.append('image', imageFile);
            }

            const response = await fetch(apiUrl, {
                method: 'POST',
                body: formData
            });

            const textResponse = await response.text();
            let data;
            try {
                data = JSON.parse(textResponse);
            } catch (err) {
                const preview = textResponse.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim().slice(0, 180);
                throw new Error(preview || 'Chat endpoint returned invalid JSON.');
            }

            if (!response.ok) {
                throw new Error(data.error || 'Chat request failed.');
            }

            pendingEl.remove();
            renderMessage('assistant', data.reply || 'No reply returned.');

            if (data.pest_result) {
                renderPestResult(data.pest_result);
            }

            speak(data.reply || '');

            loadSessions();
        } catch (err) {
            pendingEl.remove();
            setError(err.message || 'Unable to reach the assistant.');
        } finally {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-arrow-up text-sm"></i>';
            inputEl.focus();
            updateTextareaHeight();
        }
    }

    function startNewChat() {
        stopSpeaking();
        stopRecognition(true);

        const created = 'chat_' + Math.random().toString(36).slice(2) + Date.now().toString(36);
        localStorage.setItem('agricare_chat_session', created);
        messagesEl.innerHTML = '';
        renderEmptyState();
        setError('');
        clearImage();
        resetComposer();
        loadSessions();
    }

    function clearImage() {
        imageInput.value = '';
        imagePreview.src = '';
        imagePreviewContainer.classList.add('hidden');
    }

    newChatBtn.addEventListener('click', startNewChat);

    const findShopsBtn = document.getElementById('findShopsBtn');
    if (findShopsBtn) {
        findShopsBtn.addEventListener('click', () => showNearbyShops());
    }

    formEl.addEventListener('submit', function (event) {
        event.preventDefault();
        sendMessage(inputEl.value);
    });

    inputEl.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendMessage(inputEl.value);
        }
    });

    inputEl.addEventListener('input', function () {
        if (isListening) stopRecognition(true);
        if (this.value.trim()) stopSpeaking();
        updateTextareaHeight();
    });

    micBtn.addEventListener('click', function () {
        if (!recognition) {
            setError('Speech recognition is not supported in this browser.');
            return;
        }

        setError('');

        if (isListening) {
            stopRecognition(true);
            return;
        }

        stopSpeaking();

        const activeLang = window.CHAT_LANG || getUserPayload().pref_lang || 'en';
        const langMap = { en: 'en-IN', hi: 'hi-IN', gu: 'gu-IN' };
        recognition.lang = langMap[activeLang] || 'en-IN';
        recognitionTranscript = '';
        try {
            recognition.start();
        } catch (err) {
            setError('Voice input could not start. Please try again.');
        }
    });

    imgBtn.addEventListener('click', () => imageInput.click());

    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                imagePreviewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    removeImgBtn.addEventListener('click', clearImage);

    promptButtons.forEach((btn) => {
        btn.addEventListener('click', function () {
            stopSpeaking();
            stopRecognition(true);
            sendMessage(btn.dataset.prompt || '');
        });
    });

    clearChatBtn.addEventListener('click', startNewChat);
    const clearChatBtnDesktop = document.getElementById('clearChatBtnDesktop');
    if (clearChatBtnDesktop) clearChatBtnDesktop.addEventListener('click', startNewChat);

    const muteBtnDesktop = document.getElementById('muteBtnDesktop');
    const muteBtnMobile = document.getElementById('muteBtnMobile');
    const muteBtnInput = document.getElementById('muteBtnInput');
    if (muteBtnDesktop) muteBtnDesktop.addEventListener('click', toggleMute);
    if (muteBtnMobile) muteBtnMobile.addEventListener('click', toggleMute);
    if (muteBtnInput) muteBtnInput.addEventListener('click', toggleMute);

    if (window.speechSynthesis) {
        window.speechSynthesis.onvoiceschanged = function () {
            updateVoiceButtonsUI();
        };
    }

    updateMuteUI();
    updateMicUI();
    renderProfileSummary();
    startNewChat();
    loadSessions();
})();
