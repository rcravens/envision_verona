<x-layouts.app>

    <div class="max-w-6xl mx-auto m-4 p-4 flex flex-col h-screen max-h-[calc(100vh-84px)]">

        <!-- Header -->
        <div class="p-4 bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h1 class="text-2xl font-semibold">ChatBot</h1>

            <button id="new-conversation"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow">
                New Conversation
            </button>
        </div>


        <!-- Chat Box -->
        <div id="chat-box"
             class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50 dark:bg-gray-900">

            @foreach($messages as $m)
                @if($m['role'] === 'user')
                    <!-- User Message -->
                    <div class="flex justify-end">
                        <div class="max-w-xl bg-blue-600 text-white px-4 py-2 rounded-lg shadow">
                            {{ $m['content'] }}
                        </div>
                    </div>
                @else
                    <!-- Assistant Message -->
                    <div class="flex justify-start">
                        <div class="max-w-xl bg-white dark:bg-gray-800 px-4 py-2 rounded-lg shadow prose dark:prose-invert">
                            {!! \Illuminate\Support\Str::markdown($m['content']) !!}
                        </div>
                    </div>
                @endif
            @endforeach

        </div>

        <!-- Chat Input -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <form id="chat-form" class="flex space-x-3">
                <input
                    id="message"
                    type="text"
                    placeholder="Type your message..."
                    class="flex-1 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500"
                />
                <button
                    type="submit"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow">
                    Send
                </button>
            </form>
        </div>

    </div>

    @push('scripts')
        <script>
            const chatBox = document.querySelector('#chat-box');
            const form = document.querySelector('#chat-form');
            const input = document.querySelector('#message');

            function scrollChat() {
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            scrollChat();

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const msg = input.value.trim();
                if (!msg) return;

                // render user message immediately
                appendMessage('user', msg);
                input.value = '';

                fetch("{{ route('chat.response') }}", {
                    method : "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body   : JSON.stringify({message: msg})
                })
                    .then(res => res.json())
                    .then(data => {
                        appendMessage('assistant', data.reply);
                    });
            });

            function appendMessage(role, text) {
                let html = '';

                if (role === 'user') {
                    html = `
                <div class="flex justify-end">
                    <div class="max-w-xl bg-blue-600 text-white px-4 py-2 rounded-lg shadow">
                        ${escapeHtml(text)}
                    </div>
                </div>
            `;
                } else {
                    html = `
                <div class="flex justify-start">
                    <div class="max-w-xl bg-white dark:bg-gray-800 px-4 py-2 rounded-lg shadow prose dark:prose-invert">
                        ${marked.parse(text)}
                    </div>
                </div>
            `;
                }

                chatBox.insertAdjacentHTML('beforeend', html);
                scrollChat();
            }

            // Basic HTML escape for user messages
            function escapeHtml(text) {
                let div = document.createElement("div");
                div.textContent = text;
                return div.innerHTML;
            }

            document.getElementById('new-conversation').addEventListener('click', function () {
                fetch("{{ route('chat.clear') }}", {
                    method : "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Clear the chat box on the page
                            chatBox.innerHTML = '';
                        }
                    });
            });
        </script>

        <!-- Include Marked.js for client-side markdown rendering -->
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    @endpush

</x-layouts.app>

