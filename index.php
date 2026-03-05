<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT Clone - Premium</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0d1117;
            --sidebar-bg: #161b22;
            --chat-bg: #0d1117;
            --input-bg: #21262d;
            --user-msg-bg: #238636;
            --bot-msg-bg: #21262d;
            --text-color: #e6edf3;
            --border-color: #30363d;
            --accent-color: #2f81f7;
            --glass-bg: rgba(22, 27, 34, 0.7);
            --glass-border: rgba(48, 54, 61, 0.5);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            scrollbar-width: thin;
            scrollbar-color: var(--border-color) var(--sidebar-bg);
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border-color);
            transition: transform 0.3s ease;
            z-index: 100;
        }

        .new-chat-btn {
            margin: 10px;
            padding: 12px;
            background-color: transparent;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            color: var(--text-color);
            text-align: left;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            transition: background 0.2s;
        }

        .new-chat-btn:hover {
            background-color: var(--input-bg);
        }

        .history-list {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }

        .history-item {
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #c9d1d9;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: background 0.2s;
        }

        .history-item:hover, .history-item.active {
            background-color: var(--input-bg);
        }

        .settings-link {
            padding: 15px;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            color: var(--text-color);
            text-decoration: none;
            transition: hover 0.2s;
        }
        
        .settings-link:hover {
            background-color: var(--input-bg);
        }

        /* Main Chat Area */
        .main-chat {
            flex: 1;
            display: flex;
            flex-direction: column;
            position: relative;
            background-color: var(--chat-bg);
        }

        .chat-header {
            display: none; /* Only for mobile */
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
            align-items: center;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
        }

        .menu-btn {
            background: none;
            border: none;
            color: var(--text-color);
            cursor: pointer;
            font-size: 1.5rem;
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            padding-bottom: 120px;
            scroll-behavior: smooth;
        }

        .message {
            display: flex;
            padding: 20px;
            gap: 15px;
            border-bottom: 1px solid transparent;
            max-width: 800px;
            margin: 0 auto;
        }

        .message.bot {
            background-color: transparent; /* Or darker shade if desired */
            border-bottom: 1px solid var(--border-color); 
        }

        .message.user {
            background-color: transparent;
        }

        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.8rem;
        }

        .avatar.user-avatar {
            background: var(--user-msg-bg);
            color: white;
        }

        .avatar.bot-avatar {
            background: var(--accent-color);
            color: white;
        }

        .message-content {
            flex: 1;
            line-height: 1.6;
            font-size: 1rem;
        }

        /* Markdown-like styling */
        .message-content p { margin-bottom: 10px; }
        .message-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background: var(--input-bg);
            border-radius: 5px;
            overflow: hidden;
        }
        .message-content th, .message-content td {
            padding: 10px;
            border: 1px solid var(--border-color);
            text-align: left;
        }
        .message-content th { background: var(--sidebar-bg); }

        /* Input Area */
        .input-area {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(180deg, rgba(13,17,23,0), var(--bg-color) 40%);
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .input-container {
            width: 100%;
            max-width: 768px;
            position: relative;
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            padding: 10px;
        }

        textarea {
            width: 100%;
            background: transparent;
            border: none;
            color: var(--text-color);
            resize: none;
            max-height: 200px;
            min-height: 24px;
            font-size: 1rem;
            outline: none;
            overflow-y: hidden;
        }

        .send-btn {
            background: transparent; /* var(--accent-color); */
            border: none;
            border-radius: 5px;
            color: #8b949e;
            cursor: pointer;
            padding: 5px;
            margin-left: 10px;
            transition: color 0.2s;
        }
        
        .send-btn:hover {
            color: var(--accent-color);
        }

        .send-btn svg { width: 16px; height: 16px; fill: currentColor; }

        /* Mobile */
        @media (max-width: 768px) {
            .sidebar {
                position: absolute;
                height: 100%;
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .chat-header {
                display: flex;
            }
            .message {
                padding: 15px;
            }
        }
        
        .thinking {
            display: inline-block;
            font-style: italic;
            color: #8b949e;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 0.5; }
            50% { opacity: 1; }
            100% { opacity: 0.5; }
        }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button class="new-chat-btn" onclick="startNewChat()">
            <span>+</span> New chat
        </button>
        <div class="history-list" id="history-list">
            <!-- History items loaded via JS -->
        </div>
        <a href="settings.php" class="settings-link">
            <span>⚙️</span> Settings
        </a>
    </div>

    <!-- Main Chat -->
    <div class="main-chat">
        <div class="chat-header">
            <button class="menu-btn" onclick="toggleSidebar()">☰</button>
            <span style="margin-left: 15px; font-weight: 500;">ChatGPT Proxy</span>
        </div>

        <div class="messages-container" id="messages-container">
            <!-- Messages go here -->
            <div class="message bot">
                <div class="avatar bot-avatar">AI</div>
                <div class="message-content">
                    <p>Hello! I am your AI assistant. Ask me anything.</p>
                </div>
            </div>
        </div>

        <div class="input-area">
            <div class="input-container">
                <textarea id="user-input" placeholder="Send a message..." rows="1" oninput="autoResize(this)" onkeydown="handleEnter(event)"></textarea>
                <button class="send-btn" onclick="sendMessage()">
                    <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentConversationId = -1;
        const messagesContainer = document.getElementById('messages-container');
        const userInput = document.getElementById('user-input');

        document.addEventListener('DOMContentLoaded', () => {
            loadHistory();
        });

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }

        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        }

        function handleEnter(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        }

        function startNewChat() {
            currentConversationId = -1;
            messagesContainer.innerHTML = `
                <div class="message bot">
                    <div class="avatar bot-avatar">AI</div>
                    <div class="message-content"><p>Hello! I am your AI assistant. Ask me anything.</p></div>
                </div>
            `;
            // Unselect history
            document.querySelectorAll('.history-item').forEach(el => el.classList.remove('active'));
            // On mobile, close sidebar
            if (window.innerWidth <= 768) toggleSidebar();
        }

        function loadHistory() {
            fetch('api.php?action=history')
                .then(res => res.json())
                .then(data => {
                    const list = document.getElementById('history-list');
                    list.innerHTML = '';
                    data.forEach(chat => {
                        const div = document.createElement('div');
                        div.className = 'history-item';
                        div.innerText = chat.title;
                        div.onclick = () => loadConversation(chat.id, div);
                        list.appendChild(div);
                    });
                });
        }

        function loadConversation(id, element) {
            currentConversationId = id;
            document.querySelectorAll('.history-item').forEach(el => el.classList.remove('active'));
            if (element) element.classList.add('active');

            fetch(`api.php?action=load&id=${id}`)
                .then(res => res.json())
                .then(messages => {
                    messagesContainer.innerHTML = '';
                    messages.forEach(msg => {
                        appendMessage(msg.role, msg.content, false);
                    });
                    scrollToBottom();
                    if (window.innerWidth <= 768) toggleSidebar();
                });
        }

        function appendMessage(role, content, animate = true) {
            const div = document.createElement('div');
            div.className = `message ${role}`;
            
            const avatarClass = role === 'user' ? 'user-avatar' : 'bot-avatar';
            const avatarLabel = role === 'user' ? 'U' : 'AI';

            // Simple markdown-to-html (very basic)
            let formattedContent = content
                .replace(/\n/g, '<br>')
                .replace(/\| (.*?) \|/g, (match) => {
                    // Primitive table row detection just for the specific table request
                    // Ideally we'd use a real markdown parser, but for this clone task:
                    if (match.includes('---')) return ''; // skip separator lines
                    if (content.includes('| Time |')) { 
                        // It's likely a table from our chatbot response
                        // We will handle it by wrapping the whole block in a table logic if possible
                        // But for simple line-by-line:
                        return `<tr><td>${match.replace(/\|/g, '').trim()}</td></tr>`; 
                    }
                    return match;
                });

            // Specific table hack for the "daily routine" response which returns raw markdown table
            if (content.includes('| Time |')) {
                // simple parser for the specific table format
                let rows = content.split('\n');
                let tableHtml = '<table>';
                rows.forEach((row, index) => {
                    if (row.trim() === '' || row.includes('---')) return;
                    let cols = row.split('|').filter(c => c.trim() !== '');
                    if (cols.length > 0) {
                        tableHtml += '<tr>';
                        cols.forEach(col => {
                            tableHtml += index === 0 ? `<th>${col.trim()}</th>` : `<td>${col.trim()}</td>`;
                        });
                        tableHtml += '</tr>';
                    }
                });
                tableHtml += '</table>';
                formattedContent = tableHtml;
            } else {
                 // **Bold** handling
                 formattedContent = formattedContent.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            }


            div.innerHTML = `
                <div class="avatar ${avatarClass}">${avatarLabel}</div>
                <div class="message-content">${formattedContent}</div>
            `;
            messagesContainer.appendChild(div);
            // If new message
            if (animate) scrollToBottom();
        }

        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function sendMessage() {
            const text = userInput.value.trim();
            if (!text) return;

            appendMessage('user', text);
            userInput.value = '';
            userInput.style.height = 'auto';

            // Show loading
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'message bot loading-msg';
            loadingDiv.innerHTML = `
                <div class="avatar bot-avatar">AI</div>
                <div class="message-content"><span class="thinking">Thinking...</span></div>
            `;
            messagesContainer.appendChild(loadingDiv);
            scrollToBottom();

            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    message: text,
                    conversation_id: currentConversationId
                })
            })
            .then(res => res.json())
            .then(data => {
                // Remove loading
                loadingDiv.remove();
                if (data.error) {
                    appendMessage('bot', "Error: " + data.error);
                } else {
                    currentConversationId = data.conversation_id;
                    appendMessage('assistant', data.response);
                    // Reload history to show new title if new chat
                    if (currentConversationId !== -1) loadHistory();
                }
            })
            .catch(err => {
                loadingDiv.remove();
                appendMessage('bot', "Error connecting to server.");
            });
        }
    </script>
</body>
</html>
