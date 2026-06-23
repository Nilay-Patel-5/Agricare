// backend/src/services/llmService.js
const config = require('../config/env');

const geminiTextCreate = async (messages, model = null) => {
    const apiKeys = [...config.geminiApiKeys];
    if (apiKeys.length === 0 || apiKeys[0] === '') {
        return { ok: false, error: 'Gemini API key is not configured.' };
    }

    // Shuffle keys for load balancing
    apiKeys.sort(() => Math.random() - 0.5);

    // Map roles to Gemini contents payload format
    const contents = [];
    let systemInstruction = null;

    for (const msg of messages) {
        const role = msg.role || 'user';
        const text = (msg.content || '').trim();
        if (!text) continue;

        if (role === 'system') {
            systemInstruction = { parts: [{ text }] };
            continue;
        }

        contents.push({
            role: role === 'assistant' ? 'model' : 'user',
            parts: [{ text }]
        });
    }

    const payload = {
        contents,
        generationConfig: {
            temperature: 0.3,
            maxOutputTokens: 1024
        }
    };

    if (systemInstruction) {
        payload.systemInstruction = systemInstruction;
    }

    const targetModel = model || 'gemini-1.5-flash';
    const fallbackModels = Array.from(new Set([
        targetModel,
        'gemini-2.5-flash',
        'gemini-2.0-flash',
        'gemini-flash-latest',
        'gemini-2.0-flash-lite'
    ]));

    let lastError = '';

    for (const apiKey of apiKeys) {
        if (apiKey === 'YOUR_GEMINI_API_KEY_HERE') continue;

        for (const currentModel of fallbackModels) {
            const cleanModel = currentModel.replace(/^models\//, '');
            const url = `https://generativelanguage.googleapis.com/v1beta/models/${encodeURIComponent(cleanModel)}:generateContent?key=${apiKey}`;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                    signal: AbortSignal.timeout(60000)
                });

                const data = await response.json();
                if (!response.ok) {
                    const message = data?.error?.message || 'Gemini request failed.';
                    lastError = message;

                    if (response.status === 429) {
                        // Rate limit: try next key/model
                        continue;
                    }
                    if (message.includes('expired') || message.includes('invalid') || response.status === 400) {
                        // Dead key: try next key
                        break;
                    }
                    continue;
                }

                // Success
                return {
                    ok: true,
                    data,
                    model: currentModel
                };
            } catch (err) {
                lastError = err.message;
            }
        }
    }

    return {
        ok: false,
        error: lastError || 'All Gemini API keys failed or were rate-limited.'
    };
};

const geminiExtractText = (data) => {
    try {
        const parts = data?.candidates?.[0]?.content?.parts || [];
        return parts.map(p => p.text || '').join('').trim();
    } catch {
        return '';
    }
};

const groqChatCreate = async (messages, model = null) => {
    if (!config.groqApiKey) {
        return { ok: false, error: 'Groq API key is not configured.' };
    }

    const payload = {
        model: model || config.groqApiKey || 'llama-3.1-8b-instant',
        messages: messages.map(msg => ({
            role: msg.role === 'assistant' ? 'assistant' : msg.role === 'system' ? 'system' : 'user',
            content: msg.content
        })),
        temperature: 0.3,
        max_tokens: 1024
    };

    const url = `${config.groqApiKey.includes('https') ? config.groqApiKey : 'https://api.groq.com/openai/v1'}/chat/completions`;

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${config.groqApiKey}`
            },
            body: JSON.stringify(payload),
            signal: AbortSignal.timeout(60000)
        });

        const data = await response.json();
        if (!response.ok) {
            return {
                ok: false,
                error: data?.error?.message || 'Groq request failed.'
            };
        }

        return {
            ok: true,
            data
        };
    } catch (err) {
        return {
            ok: false,
            error: err.message
        };
    }
};

const groqExtractText = (data) => {
    return data?.choices?.[0]?.message?.content?.trim() || '';
};

module.exports = {
    geminiTextCreate,
    geminiExtractText,
    groqChatCreate,
    groqExtractText
};
