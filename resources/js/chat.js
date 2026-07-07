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

// ── Escape HTML to prevent XSS ──
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}

// ── Append message to UI ──
function appendMessage(content, isMine, time, senderName, filePath, fileName, fileType) {
    let fileHtml = '';
    if (filePath) {
        const isImage = fileType && fileType.startsWith('image/');
        if (isImage) {
            fileHtml = `<img src="${filePath}" class="mt-2 rounded-xl max-w-xs max-h-48 object-cover cursor-pointer" onclick="window.open('${filePath}')">`;
        } else {
            fileHtml = `
                <a href="${filePath}" target="_blank"
                   class="mt-2 flex items-center gap-2 bg-white/20 hover:bg-white/30 px-3 py-2 rounded-xl text-xs transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    ${escapeHtml(fileName)}
                </a>`;
        }
    }

    const bubbleContent = `
        ${content ? `<div class="px-4 py-2.5 text-sm leading-relaxed break-words
            ${isMine ? 'text-white bubble-mine' : 'bg-white text-gray-800 bubble-theirs border border-gray-100 shadow-sm'}">
            ${escapeHtml(content)}
        </div>` : ''}
        ${fileHtml}
    `;

    const wrapper = document.createElement('div');
    wrapper.className = `flex items-end gap-2 ${isMine ? 'flex-row-reverse' : ''} message-enter`;
    wrapper.innerHTML = `
        ${!isMine ? `
        <div class="w-8 h-8 rounded-full text-white flex items-center justify-center font-semibold text-xs shrink-0 mb-1"
             style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
            ${escapeHtml(senderName ? senderName.charAt(0).toUpperCase() : '?')}
        </div>` : ''}
        <div class="flex flex-col ${isMine ? 'items-end' : 'items-start'} max-w-xs lg:max-w-md">
            ${!isMine ? `<span class="text-xs font-medium text-gray-600 mb-1 ml-1">${escapeHtml(senderName)}
                <span class="text-gray-400 font-normal ml-1">${time}</span>
            </span>` : ''}
            ${bubbleContent}
            ${isMine ? `<span class="text-[10px] text-gray-400 mt-1 mr-1">${time}</span>` : ''}
        </div>
    `;

    messagesEl.appendChild(wrapper);
    scrollToBottom();
}

// ── File type map ──
const fileTypeMap = {
    pdf:  { icon: '📄', bg: '#FAECE7', color: '#993C1D', label: 'PDF' },
    doc:  { icon: '📝', bg: '#E6F1FB', color: '#185FA5', label: 'DOC' },
    docx: { icon: '📝', bg: '#E6F1FB', color: '#185FA5', label: 'DOCX' },
    xls:  { icon: '📊', bg: '#EAF3DE', color: '#3B6D11', label: 'XLS' },
    xlsx: { icon: '📊', bg: '#EAF3DE', color: '#3B6D11', label: 'XLSX' },
    ppt:  { icon: '📑', bg: '#FAECE7', color: '#993C1D', label: 'PPT' },
    pptx: { icon: '📑', bg: '#FAECE7', color: '#993C1D', label: 'PPTX' },
    jpg:  { icon: '🖼️', bg: '#FBEAF0', color: '#993556', label: 'IMAGE' },
    jpeg: { icon: '🖼️', bg: '#FBEAF0', color: '#993556', label: 'IMAGE' },
    png:  { icon: '🖼️', bg: '#FBEAF0', color: '#993556', label: 'IMAGE' },
    gif:  { icon: '🖼️', bg: '#FBEAF0', color: '#993556', label: 'GIF' },
    mp4:  { icon: '🎬', bg: '#EEEDFE', color: '#534AB7', label: 'VIDEO' },
    mov:  { icon: '🎬', bg: '#EEEDFE', color: '#534AB7', label: 'VIDEO' },
    mp3:  { icon: '🎵', bg: '#EEEDFE', color: '#534AB7', label: 'AUDIO' },
    zip:  { icon: '🗜️', bg: '#FAEEDA', color: '#854F0B', label: 'ZIP' },
    rar:  { icon: '🗜️', bg: '#FAEEDA', color: '#854F0B', label: 'RAR' },
    txt:  { icon: '📃', bg: '#F1EFE8', color: '#5F5E5A', label: 'TXT' },
    csv:  { icon: '📋', bg: '#EAF3DE', color: '#3B6D11', label: 'CSV' },
};

function getFileType(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    return fileTypeMap[ext] || { icon: '📁', bg: '#F1EFE8', color: '#5F5E5A', label: ext.toUpperCase() };
}

function formatSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return Math.round(bytes / 1024) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

// ── File handling ──
let selectedFile = null;

window.handleFileSelect = function(input) {
     const file = input.files[0];
    if (!file) return;

     const maxBytes = 10 * 1024 * 1024; // 10MB
    if (file.size > maxBytes) {
        showFileError(`"${file.name}" is too large. Maximum file size is 10MB.`);
        input.value = ''; // reset the input
        return;           // stop here — no preview, no selectedFile
    }

    // Only assign and show preview if validation passed
    selectedFile = file;

    const t       = getFileType(selectedFile.name);
    const preview = document.getElementById('file-preview');

    preview.innerHTML = `
        <div style="display:flex;align-items:center;gap:10px;padding:8px 12px;
                    background:#fff;border:1px solid #e5e7eb;border-radius:12px;width:fit-content;max-width:260px;">
            <div style="width:36px;height:36px;border-radius:8px;background:${t.bg};
                        display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">
                ${t.icon}
            </div>
            <div style="flex:1;overflow:hidden;">
                <div style="font-size:12px;font-weight:600;white-space:nowrap;overflow:hidden;
                            text-overflow:ellipsis;color:#111;max-width:160px;">
                    ${escapeHtml(selectedFile.name)}
                </div>
                <div style="margin-top:3px;display:flex;align-items:center;gap:5px;">
                    <span style="background:${t.bg};color:${t.color};padding:1px 6px;border-radius:4px;
                                 font-size:10px;font-weight:700;letter-spacing:0.3px;">
                        ${t.label}
                    </span>
                    <span style="font-size:11px;color:#888;">${formatSize(selectedFile.size)}</span>
                </div>
            </div>
            <button onclick="window.removeFile()"
                    style="width:20px;height:20px;border-radius:50%;border:1px solid #e5e7eb;
                           background:none;cursor:pointer;font-size:12px;color:#999;
                           display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                ✕
            </button>
        </div>
    `;
    preview.classList.remove('hidden');
}
function showFileError(message) {
    const preview = document.getElementById('file-preview');
    preview.innerHTML = `
        <div style="display:flex;align-items:center;gap:8px;padding:8px 12px;
                    background:#FEF2F2;border:1px solid #FECACA;border-radius:12px;width:fit-content;max-width:300px;">
            <svg style="width:16px;height:16px;color:#DC2626;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span style="font-size:12px;color:#DC2626;font-weight:500;">${escapeHtml(message)}</span>
            <button onclick="window.removeFile()"
                    style="width:20px;height:20px;border-radius:50%;border:1px solid #FECACA;
                           background:none;cursor:pointer;font-size:12px;color:#DC2626;
                           display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                ✕
            </button>
        </div>
    `;
    preview.classList.remove('hidden');

    // Auto-dismiss after 4 seconds
    setTimeout(() => {
        preview.classList.add('hidden');
        preview.innerHTML = '';
    }, 4000);
}

window.removeFile = function() {
    selectedFile = null;
    // Reset all file inputs
    ['file-input-image', 'file-input-video', 'file-input-document', 'file-input-audio'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    const preview = document.getElementById('file-preview');
    preview.classList.add('hidden');
    preview.innerHTML = '';
}

// ── Send message ──  BUG FIX 1: was missing async keyword
window.sendMessage = async function() {
    const content = msgInput.value.trim();
    if (!content && !selectedFile) return;

    const formData = new FormData();
    if (content) formData.append('content', content);
    if (selectedFile) formData.append('file', selectedFile);

    msgInput.value = '';
    msgInput.style.height = '42px';
    window.removeFile();

    try {
        const response = await fetch(`/chat/${window.CONVERSATION_ID}/send`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData,
        });

        const data = await response.json();
        if (data.id) {
            appendMessage(data.content, true, data.created_at, data.sender_name, data.file_path, data.file_name, data.file_type);
        }
    } catch (error) {
        console.error('Send error:', error);
    }
}

// ── Send button click ──  BUG FIX 2: was calling sendMessage (undefined), now window.sendMessage
sendBtn.addEventListener('click', window.sendMessage);

// ── Enter to send (Shift+Enter = new line) ──
msgInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        window.sendMessage();
    }
});

// ── Auto resize textarea ──
msgInput.addEventListener('input', () => {
    msgInput.style.height = '42px';
    msgInput.style.height = Math.min(msgInput.scrollHeight, 120) + 'px';
});

// ── Typing indicator ──
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

// ── Echo: listen for incoming messages ──  BUG FIX 3: was not passing file data to appendMessage
window.Echo.private(`conversation.${window.CONVERSATION_ID}`)
    .listen('.message.sent', (e) => {
        console.log('📨 Message received:', e);
        if (parseInt(e.user_id) !== parseInt(window.AUTH_USER_ID)) {
            appendMessage(e.content, false, e.created_at, e.sender_name, e.file_path, e.file_name, e.file_type);
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

console.log('🔐 Subscribing to private channel:', `conversation.${window.CONVERSATION_ID}`);