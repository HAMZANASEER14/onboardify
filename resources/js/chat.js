const messagesEl = document.getElementById('messages');
const msgInput   = document.getElementById('msg-input');
const sendBtn    = document.getElementById('send-btn');

// ── Typing indicator ──
const typingEl = document.createElement('div');
typingEl.className = 'px-5 pb-2 text-xs text-gray-400 italic min-h-[20px]';
messagesEl.after(typingEl);

let typingTimeout = null;
let typingSent    = false;

// ── Scroll to bottom ──
function scrollToBottom() {
    messagesEl.scrollTop = messagesEl.scrollHeight;
}
scrollToBottom();

// ── Append message to UI ──
function appendMessage(content, isMine, time, senderName) {
    const div = document.createElement('div');
    div.className = `flex items-end gap-2 message-enter ${isMine ? 'flex-row-reverse' : ''}`;

    if (isMine) {
        div.innerHTML = `
            <div class="flex flex-col items-end max-w-xs lg:max-w-sm">
                <div class="px-4 py-2.5 text-sm leading-relaxed bg-blue-500 text-white"
                     style="border-radius: 18px 18px 4px 18px; word-break: break-word;">
                    ${escapeHtml(content)}
                </div>
                <span class="text-xs text-gray-400 mt-1 mr-1">${time}</span>
            </div>
        `;
    } else {
        const initial = (senderName || 'U').charAt(0).toUpperCase();
        div.innerHTML = `
            <div class="w-7 h-7 rounded-full bg-blue-500 text-white flex items-center justify-center font-semibold text-xs shrink-0 mb-1">
                ${initial}
            </div>
            <div class="flex flex-col items-start max-w-xs lg:max-w-sm">
                <span class="text-xs font-medium text-gray-600 mb-1 ml-1">
                    ${escapeHtml(senderName || '')}
                    <span class="text-gray-400 font-normal ml-1">${time}</span>
                </span>
                <div class="px-4 py-2.5 text-sm leading-relaxed bg-gray-100 text-gray-800"
                     style="border-radius: 18px 18px 18px 4px; word-break: break-word;">
                    ${escapeHtml(content)}
                </div>
            </div>
        `;
    }

    messagesEl.appendChild(div);
    scrollToBottom();
}

// ── Escape HTML to prevent XSS ──
function escapeHtml(text) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}

// ── Send message ──
async function sendMessage() {
    const content = msgInput.value.trim();
    if (!content) return;

    msgInput.value = '';
    msgInput.style.height = '42px';
    typingEl.textContent  = '';

    try {
        const response = await fetch(`/chat/${window.CONVERSATION_ID}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ content }),
        });

        const data = await response.json();

        if (data.id && data.content) {
            appendMessage(data.content, true, data.created_at, data.sender_name);
        }

    } catch (error) {
        console.error('Send error:', error);
    }
}

// ── Send button click ──
sendBtn.addEventListener('click', sendMessage);

// ── Enter to send (Shift+Enter = new line) ──
msgInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

// ── Auto resize textarea ──
msgInput.addEventListener('input', () => {
    msgInput.style.height = '42px';
    msgInput.style.height = Math.min(msgInput.scrollHeight, 120) + 'px';
});

// ── Typing indicator — throttled to once per 300ms ──
msgInput.addEventListener('input', () => {
    if (typingSent) return;

    typingSent = true;
    setTimeout(() => { typingSent = false; }, 300);

    fetch(`/chat/${window.CONVERSATION_ID}/typing`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    }).catch(err => console.error('Typing error:', err));
});

// ── Echo: listen for incoming messages ──
window.Echo.private(`conversation.${window.CONVERSATION_ID}`)
    .listen('.message.sent', (e) => {
        console.log('📨 Message received:', e);
        if (parseInt(e.user_id) !== parseInt(window.AUTH_USER_ID) && e.content) {
            appendMessage(e.content, false, e.created_at, e.sender_name);
            typingEl.textContent = '';
        }
    })
    .listen('.user.typing', (e) => {
        if (parseInt(e.user_id) === parseInt(window.AUTH_USER_ID)) return;

        typingEl.textContent = `${e.user_name} is typing...`;

        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(() => {
            typingEl.textContent = '';
        }, 2000);
    })
    .error((error) => {
        console.error('❌ Private channel error:', error);
    });

// Debug: Log channel subscription
console.log('🔐 Subscribing to private channel:', `conversation.${window.CONVERSATION_ID}`);