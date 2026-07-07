{{--
    New Chat / Create Group picker modal.
    Include this partial in your chat layout, e.g.:
    @include('chat.partials.new-chat-modal')

    Trigger it from anywhere with:
    <button onclick="openNewChatModal()">New chat</button>
--}}

<div id="new-chat-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[80vh] flex flex-col overflow-hidden">

        {{-- Tabs --}}
        <div class="flex border-b border-gray-100 shrink-0">
            <button type="button" data-tab="direct"
                    class="chat-tab flex-1 text-sm font-semibold py-3.5 border-b-2 border-blue-600 text-blue-600">
                New chat
            </button>
            @if(in_array(auth()->user()->role, ['admin', 'employee']))
                <button type="button" data-tab="group"
                        class="chat-tab flex-1 text-sm font-semibold py-3.5 border-b-2 border-transparent text-gray-400">
                    Create group
                </button>
            @endif
            <button type="button" onclick="closeNewChatModal()"
                    class="px-4 text-gray-400 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Search box (shared by both tabs) --}}
        <div class="p-4 pb-2 shrink-0">
            <div class="relative">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                </svg>
                <input
                    type="text"
                    id="user-search-input"
                    placeholder="Search by name..."
                    autocomplete="off"
                    class="w-full pl-9 pr-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400"
                >
            </div>
        </div>

        {{-- Group name field — only visible on group tab --}}
        <div id="group-name-field" class="hidden px-4 pb-2 shrink-0 space-y-2">
            <input
                type="text"
                id="group-name-input"
                placeholder="Group name"
                maxlength="255"
                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400"
            >
            <div id="selected-chips" class="flex flex-wrap gap-1.5"></div>
        </div>

        {{-- Results list --}}
        <div id="user-results" class="flex-1 overflow-y-auto px-2 pb-2">
            <div class="text-center text-gray-400 text-sm py-8">Type a name to search your team</div>
        </div>

        {{-- Footer — only for group tab --}}
        <div id="group-footer" class="hidden border-t border-gray-100 p-3 shrink-0">
            <button type="button" id="create-group-btn"
                    disabled
                    class="w-full bg-blue-600 disabled:bg-gray-200 disabled:text-gray-400 text-white text-sm font-semibold py-2.5 rounded-lg transition">
                Create group
            </button>
        </div>

    </div>
</div>

<script>
(function () {
    const modal          = document.getElementById('new-chat-modal');
    const searchInput     = document.getElementById('user-search-input');
    const resultsBox      = document.getElementById('user-results');
    const tabs             = document.querySelectorAll('.chat-tab');
    const groupNameField   = document.getElementById('group-name-field');
    const groupNameInput   = document.getElementById('group-name-input');
    const groupFooter      = document.getElementById('group-footer');
    const createGroupBtn   = document.getElementById('create-group-btn');
    const selectedChips    = document.getElementById('selected-chips');

    let activeTab = 'direct';
    let selectedMembers = new Map(); // id -> name
    let debounceTimer = null;

    window.openNewChatModal = function () {
        modal.classList.remove('hidden');
        searchInput.value = '';
        resultsBox.innerHTML = '<div class="text-center text-gray-400 text-sm py-8">Type a name to search your team</div>';
        searchInput.focus();
    };

    window.closeNewChatModal = function () {
        modal.classList.add('hidden');
        selectedMembers.clear();
        renderChips();
        groupNameInput.value = '';
        updateCreateButtonState();
    };

    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeNewChatModal();
    });

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            activeTab = tab.dataset.tab;
            tabs.forEach(t => {
                t.classList.toggle('border-blue-600', t === tab);
                t.classList.toggle('text-blue-600', t === tab);
                t.classList.toggle('border-transparent', t !== tab);
                t.classList.toggle('text-gray-400', t !== tab);
            });
            const isGroup = activeTab === 'group';
            groupNameField.classList.toggle('hidden', !isGroup);
            groupFooter.classList.toggle('hidden', !isGroup);
            resultsBox.innerHTML = '<div class="text-center text-gray-400 text-sm py-8">Type a name to search your team</div>';
            searchInput.value = '';
        });
    });

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const q = searchInput.value.trim();
        debounceTimer = setTimeout(function () {
            fetchUsers(q);
        }, 250);
    });

    function fetchUsers(q) {
        const url = '{{ route("chat.search-users") }}' + (q ? ('?q=' + encodeURIComponent(q)) : '');
        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(renderResults)
            .catch(() => {
                resultsBox.innerHTML = '<div class="text-center text-red-500 text-sm py-8">Could not load results</div>';
            });
    }

    function renderResults(users) {
        if (!users.length) {
            resultsBox.innerHTML = '<div class="text-center text-gray-400 text-sm py-8">No teammates found</div>';
            return;
        }
        resultsBox.innerHTML = '';
        users.forEach(function (u) {
            const row = document.createElement('div');
            row.className = 'flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 cursor-pointer transition';

            const isSelected = selectedMembers.has(u.id);

            row.innerHTML =
                '<div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-xs shrink-0">' + u.initial + '</div>' +
                '<div class="flex-1 min-w-0">' +
                    '<p class="text-sm font-medium text-gray-900 truncate">' + escapeHtml(u.name) + '</p>' +
                    '<p class="text-xs text-gray-400 capitalize">' + escapeHtml(u.role) + '</p>' +
                '</div>' +
                (activeTab === 'group'
                    ? '<div class="w-5 h-5 rounded border-2 ' + (isSelected ? 'bg-blue-600 border-blue-600' : 'border-gray-300') + ' flex items-center justify-center shrink-0"></div>'
                    : '');

            row.addEventListener('click', function () {
                if (activeTab === 'direct') {
                    startDirectChat(u.id);
                } else {
                    toggleMember(u.id, u.name);
                    renderResults(users);
                }
            });

            resultsBox.appendChild(row);
        });
    }

    function toggleMember(id, name) {
        if (selectedMembers.has(id)) {
            selectedMembers.delete(id);
        } else {
            selectedMembers.set(id, name);
        }
        renderChips();
        updateCreateButtonState();
    }

    function renderChips() {
        selectedChips.innerHTML = '';
        selectedMembers.forEach(function (name, id) {
            const chip = document.createElement('span');
            chip.className = 'inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-medium px-2 py-1 rounded-full';
            chip.innerHTML = escapeHtml(name) + ' <button type="button" data-id="' + id + '" class="remove-chip text-blue-400 hover:text-blue-700">&times;</button>';
            selectedChips.appendChild(chip);
        });
        selectedChips.querySelectorAll('.remove-chip').forEach(function (btn) {
            btn.addEventListener('click', function () {
                selectedMembers.delete(Number(btn.dataset.id));
                renderChips();
                updateCreateButtonState();
                fetchUsers(searchInput.value.trim());
            });
        });
    }

    function updateCreateButtonState() {
        const ready = selectedMembers.size > 0 && groupNameInput.value.trim().length > 0;
        createGroupBtn.disabled = !ready;
    }

    groupNameInput.addEventListener('input', updateCreateButtonState);

    function startDirectChat(userId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/chat/start/' + userId;
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }

    createGroupBtn.addEventListener('click', function () {
        if (createGroupBtn.disabled) return;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("groups.store") }}';
        let html = '@csrf';
        html += '<input type="hidden" name="name" value="' + escapeHtml(groupNameInput.value.trim()) + '">';
        selectedMembers.forEach(function (name, id) {
            html += '<input type="hidden" name="member_ids[]" value="' + id + '">';
        });
        form.innerHTML = html;
        document.body.appendChild(form);
        form.submit();
    });

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
})();
</script>