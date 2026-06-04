const msgBox  = document.getElementById('messages');
const input   = document.getElementById('msg-input');
const sendBtn = document.getElementById('send-btn');
const csrf    = document.querySelector('meta[name="csrf-token"]').content;

// Scroll to bottom on load
msgBox.scrollTop = msgBox.scrollHeight;

// Reverb presence channel
window.Echo.join(`group.${window.GROUP_ID}`)
    .here(users => updateOnlineCount(users.length))
    .joining(() => updateOnlineCount())
    .leaving(() => updateOnlineCount())
    .listen('.group.message', (e) => {
        if (e.user.id !== window.AUTH_ID) {
            appendMessage(e.user.name, e.message, e.created_at, false);
        }
    });

// Send on button click
sendBtn.addEventListener('click', sendMessage);

// Send on Enter key
input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

function sendMessage() {
    const text = input.value.trim();
    if (!text) return;

    fetch(window.STORE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({ message: text }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'ok') {
            appendMessage(window.AUTH_NAME, text, 'Just now', true);
            input.value = '';
        }
    })
    .catch(err => console.error('Send error:', err));
}

function appendMessage(name, text, time, isMine) {
    const div = document.createElement('div');
    div.className = `message ${isMine ? 'mine' : 'theirs'}`;
    div.innerHTML = `
        ${!isMine ? `<span class="sender-name">${name}</span>` : ''}
        <div class="bubble">${escapeHtml(text)}</div>
        <span class="time">${time}</span>
    `;
    msgBox.appendChild(div);
    msgBox.scrollTop = msgBox.scrollHeight;
}

function updateOnlineCount(count) {
    const el = document.getElementById('online-count');
    if (el && count !== undefined) {
        el.textContent = `${count} online`;
    }
}

function escapeHtml(text) {
    return text
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}