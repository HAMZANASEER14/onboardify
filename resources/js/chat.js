// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster:       'reverb',
//     key:               import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost:            import.meta.env.VITE_REVERB_HOST,
//     wsPort:            import.meta.env.VITE_REVERB_PORT,
//     wssPort:           import.meta.env.VITE_REVERB_PORT,
//     forceTLS:          (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });

// Echo is already set up in app.js
const messagesEl = document.getElementById('messages');
const msgInput   = document.getElementById('msg-input');
const sendBtn    = document.getElementById('send-btn');

// typing indicator
const typingEl = document.createElement('div');
typingEl.style.cssText = `
    font-size: 13px;
    color: #9ca3af;
    padding: 0 20px 8px;
    min-height: 24px;
    font-style: italic;
`;
document.getElementById('input-area').before(typingEl);

let typingTimeout = null;
let typingSent    = false;

function scrollToBottom() {
    messagesEl.scrollTop = messagesEl.scrollHeight;
}
scrollToBottom();

function appendMessage(content, isMine, time) {
    const div = document.createElement('div');
    div.className = `message ${isMine ? 'mine' : 'theirs'}`;
    div.innerHTML = `
        <div class="bubble">${content}</div>
        <span class="time">${time}</span>
    `;
    messagesEl.appendChild(div);
    scrollToBottom();
}

async function sendMessage() {
    const content = msgInput.value.trim();
    if (!content) return;

    msgInput.value = '';
    msgInput.style.height = 'auto';
    typingEl.textContent = '';

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

        if (data.id) {
            appendMessage(data.content, true, data.created_at);
        }

    } catch (error) {
        console.error('Send error:', error);
    }
}

sendBtn.addEventListener('click', sendMessage);

msgInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

// auto resize textarea
msgInput.addEventListener('input', () => {
    msgInput.style.height = 'auto';
    msgInput.style.height = msgInput.scrollHeight + 'px';
});

// typing throttle — fires once every 300ms
msgInput.addEventListener('input', () => {
    if (typingSent) return;

    typingSent = true;
    setTimeout(() => { typingSent = false; }, 300);

    fetch(`/chat/${window.CONVERSATION_ID}/typing`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    });
});

// Echo listeners
window.Echo.private(`conversation.${window.CONVERSATION_ID}`)
    .listen('.message.sent', (e) => {
        if (parseInt(e.user_id) !== parseInt(window.AUTH_USER_ID)) {
            appendMessage(e.content, false, e.created_at);
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
    });