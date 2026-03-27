<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AgriCare Assistant (ChatGPT Style)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="output.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #212121; color: #ececf1; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #565869; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #8e8ea0; }
        .history-group-title {
            font-size: 0.7rem; font-weight: 600; color: #8e8ea0; padding: 1rem 0.5rem 0.25rem;
        }
        .nav-item { transition: background 0.2s; color: #ececf1; }
        .nav-item:hover { background-color: #2a2b32; }
        .nav-item.active { background-color: #343541; font-weight: 500; }
        .prose a { color: #8db5ff; text-decoration: underline; }
    </style>
</head>

<body class="h-screen w-screen flex overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-[260px] bg-[#171717] flex-shrink-0 flex flex-col hidden md:flex">
        <div class="p-3 flex items-center justify-between">
            <button id="newChatBtn" class="flex-1 flex items-center gap-3 p-3 rounded-lg hover:bg-[#202123] text-sm text-left transition-colors">
                <i class="fas fa-plus"></i>
                <span class="font-medium">New chat</span>
                <span class="ml-auto w-4 h-4 text-xs flex items-center justify-center opacity-60"><i class="fas fa-pen-to-square"></i></span>
            </button>
        </div>
        
        <!-- Sidebar Custom Tools (e.g. Find Shops) -->
        <div class="px-3 pb-2 border-b border-white/10">
            <button id="findShopsBtn" class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-[#202123] text-sm text-left transition-colors text-emerald-400">
                <i class="fas fa-map-marked-alt"></i> Find Nearby Shops
            </button>
            <div class="grid grid-cols-1 gap-1 mt-1">
                <button class="prompt-btn w-full flex items-center gap-3 p-2.5 rounded-lg hover:bg-[#202123] text-xs text-left text-gray-300" data-prompt="Show recent mandi price guidance for my crop and district.">
                    <i class="fas fa-chart-line text-emerald-500"></i> Mandi Prices
                </button>
                <button class="prompt-btn w-full flex items-center gap-3 p-2.5 rounded-lg hover:bg-[#202123] text-xs text-left text-gray-300" data-prompt="Which subsidy can help with drip irrigation for my farm?">
                    <i class="fas fa-hand-holding-dollar text-blue-400"></i> Subsidies
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-3" id="sessionHistoryList">
            <!-- History populated by JS -->
        </div>

        <div class="p-3 border-t border-white/10 flex flex-col gap-1 text-sm">
            <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-[#202123]">
                <div class="w-7 h-7 rounded-sm bg-blue-600 flex items-center justify-center text-white"><i class="fas fa-user-tractor"></i></div>
                <div id="profileSummary" class="truncate flex-1 text-xs">Loading profile...</div>
            </div>
            <a href="../dashboard/farmer.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-[#202123] text-gray-300">
                <i class="fas fa-arrow-left"></i> To Dashboard
            </a>
            <p id="modelBadge" class="px-3 py-1 text-[10px] text-gray-500 mx-auto">AI model pending</p>
        </div>
    </aside>

    <!-- Main Chat Area -->
    <main class="flex-1 flex flex-col min-w-0 bg-[#212121] relative">
        <header class="h-12 flex items-center px-4 md:hidden border-b border-white/10 text-gray-200">
            <button class="text-gray-400 hover:text-white mr-3"><i class="fas fa-bars"></i></button>
            <span class="font-medium text-sm">AgriCare</span>
            <div class="ml-auto flex gap-4 text-gray-400">
                <button id="muteBtnMobile" class="text-xs hover:text-white" title="Toggle AI Voice"><i class="fas fa-volume-up"></i></button>
                <button id="clearChatBtn" class="text-xs hover:text-white"><i class="fas fa-trash"></i></button>
            </div>
        </header>
        <div class="hidden md:flex absolute top-4 right-4 gap-4 z-10 text-xs font-medium text-gray-500">
            <button id="muteBtnDesktop" class="hover:text-gray-300 transition" title="Toggle AI voice reproduction"><i class="fas fa-volume-up"></i> Voice <span>On</span></button>
            <button id="clearChatBtnDesktop" class="hover:text-gray-300 transition">Clear chat</button>
        </div>

        <!-- Messages Container -->
        <div id="chatMessages" class="flex-1 overflow-y-auto w-full pb-36 pt-6 scroll-smooth">
            <!-- Populated by JS -->
        </div>
        
        <!-- Error Bar -->
        <div id="chatError" class="hidden absolute top-12 left-1/2 -translate-x-1/2 z-20 bg-red-500/90 text-white px-4 py-2 rounded-lg text-sm shadow-lg backdrop-blur-sm max-w-sm truncate"></div>

        <!-- Input Area -->
        <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-[#212121] via-[#212121] to-transparent pt-6 md:pt-10">
            <div class="max-w-3xl mx-auto px-4 pb-4 md:pb-6 relative">
                
                <form id="chatForm">
                    <div id="imagePreviewContainer" class="hidden absolute -top-16 left-4 bg-[#2f2f2f] p-1.5 rounded-lg border border-gray-600 shadow-lg">
                        <img id="imagePreview" src="" class="h-12 w-12 object-cover rounded">
                        <button type="button" id="removeImgBtn" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] shadow"><i class="fas fa-times"></i></button>
                    </div>

                    <div class="relative flex items-center bg-[#2f2f2f] rounded-2xl border border-gray-600 focus-within:border-gray-500 focus-within:bg-[#383838] transition-colors shadow-sm">
                        
                        <div class="flex items-center pl-3">
                            <input type="file" id="imageInput" class="hidden" accept="image/*">
                            <button type="button" id="imgBtn" class="p-2 text-gray-400 hover:text-white rounded-lg transition-colors" title="Attach image">
                                <i class="fas fa-paperclip text-lg"></i>
                            </button>
                            <button type="button" id="micBtn" class="p-2 text-gray-400 hover:text-white rounded-lg transition-colors" title="Voice dictation">
                                <i class="fas fa-microphone text-lg"></i>
                            </button>
                            <button type="button" id="muteBtnInput" class="p-2 text-gray-400 hover:text-white rounded-lg transition-colors" title="Toggle AI Auto-Read">
                                <i class="fas fa-volume-up text-lg"></i>
                            </button>
                        </div>
                        
                        <textarea id="chatInput" rows="1" class="flex-1 bg-transparent text-[#ececf1] placeholder-gray-400 py-4 px-2 min-h-[56px] max-h-[200px] resize-none focus:outline-none text-[15px]" placeholder="Message AgriCare Assistant..."></textarea>
                        
                        <div class="pr-3 flex items-center">
                            <button type="submit" id="sendBtn" class="p-2 bg-white hover:bg-gray-200 text-black rounded-xl transition-colors disabled:opacity-50 disabled:bg-gray-600 disabled:text-gray-400 w-8 h-8 flex items-center justify-center">
                                <i class="fas fa-arrow-up text-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="mt-2 text-center text-[11px] text-gray-400">
                    AgriBot can make mistakes. Please verify important agricultural information.
                </div>
            </div>
        </div>
    </main>

    <script src="js/chatbot.js"></script>
</body>
</html>
