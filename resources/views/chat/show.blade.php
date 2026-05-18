@extends('layouts.app')

@section('content')
<div class="chat-page-container">
    <div class="chat-sidebar d-none d-md-flex">
        <div class="sidebar-header">
            <h2>Messages</h2>
        </div>
        <div class="conversation-list">
            @php
                $conversations = \App\Models\Conversation::where('buyer_id', auth()->id())
                    ->orWhere('seller_id', auth()->id())
                    ->with(['buyer', 'seller'])
                    ->orderBy('last_message_at', 'desc')
                    ->get();
            @endphp
            @foreach($conversations as $conv)
                @php
                    $otherUser = (auth()->id() === $conv->buyer_id) ? $conv->seller : $conv->buyer;
                    $unreadCount = $conv->messages()->where('sender_id', '!=', auth()->id())->where('is_read', false)->count();
                @endphp
                <a href="{{ route('chat.show', $conv->id) }}" class="conversation-item {{ $conversation->id === $conv->id ? 'active' : '' }}">
                    <div class="avatar">
                        {{ substr($otherUser->name, 0, 1) }}
                    </div>
                    <div class="conv-info">
                        <div class="conv-header">
                            <span class="user-name">{{ $otherUser->name }}</span>
                            <span class="time">{{ $conv->last_message_at ? $conv->last_message_at->diffForHumans(null, true) : '' }}</span>
                        </div>
                        <div class="conv-preview">
                            <span class="last-msg">
                                @if($conv->order_id)
                                    <span class="order-badge">Order #{{ $conv->order_id }}</span>
                                @endif
                                {{ $conv->messages()->latest()->first()->body ?? 'No messages yet' }}
                            </span>
                            @if($unreadCount > 0)
                                <span class="unread-badge">{{ $unreadCount }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="chat-main">
        <div class="chat-header">
            @php
                $otherUser = (auth()->id() === $conversation->buyer_id) ? $conversation->seller : $conversation->buyer;
            @endphp
            <div class="user-meta">
                <div class="avatar">
                    {{ substr($otherUser->name, 0, 1) }}
                </div>
                <div class="details">
                    <h3>{{ $otherUser->name }}</h3>
                    <p>{{ $otherUser->role === 'seller' ? 'Seller' : 'Buyer' }}</p>
                </div>
            </div>
            @if($conversation->order_id)
                <div class="order-info">
                    <a href="{{ route('orders.show', $conversation->order_id) }}" class="order-link">
                        Inquiry for Order #{{ $conversation->order_id }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a>
                </div>
            @endif
        </div>

        <div class="messages-container" id="messages-container">
            @foreach($messages as $message)
                <div class="message-wrapper {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }}">
                    <div class="message-bubble">
                        {{ $message->body }}
                        <span class="msg-time">{{ $message->created_at->format('H:i') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <form id="chat-form" class="chat-input-area" action="{{ route('chat.send', $conversation->id) }}" method="POST">
            @csrf
            <textarea name="body" id="message-body" placeholder="Type a message..." required></textarea>
            <button type="submit" id="send-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </form>
    </div>
</div>

<style>
    /* Reuse styles from index.blade.php but add show specific ones */
    .chat-page-container {
        display: flex;
        height: calc(100vh - 160px);
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        margin: 1rem 0;
    }

    .chat-sidebar {
        width: 350px;
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        background: var(--bg-body);
    }

    .sidebar-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
    }

    .sidebar-header h2 {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
    }

    .conversation-list {
        flex: 1;
        overflow-y: auto;
    }

    .conversation-item {
        display: flex;
        padding: 1rem 1.5rem;
        gap: 1rem;
        text-decoration: none;
        color: var(--text-primary);
        border-bottom: 1px solid var(--border);
        transition: background 0.2s;
    }

    .conversation-item:hover {
        background: var(--bg-hover);
    }

    .conversation-item.active {
        background: var(--bg-hover);
        border-left: 4px solid var(--primary);
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f0f0f0;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }

    .conversation-item.active .avatar {
        background: var(--primary);
        color: white;
    }

    .conv-info {
        flex: 1;
        min-width: 0;
    }

    .conv-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.95rem;
    }

    .time {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .last-msg {
        font-size: 0.85rem;
        color: var(--text-secondary);
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: var(--bg-surface);
    }

    .chat-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--bg-body);
    }

    .user-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-meta h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .user-meta p {
        margin: 0;
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    .order-link {
        font-size: 0.85rem;
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1rem;
        background: var(--bg-hover);
        border-radius: 20px;
    }

    .messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        background: var(--bg-surface);
    }

    .message-wrapper {
        display: flex;
        width: 100%;
    }

    .message-wrapper.sent {
        justify-content: flex-end;
    }

    .message-wrapper.received {
        justify-content: flex-start;
    }

    .message-bubble {
        max-width: 70%;
        padding: 0.8rem 1.2rem;
        border-radius: 18px;
        font-size: 0.95rem;
        position: relative;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .sent .message-bubble {
        background: var(--primary);
        color: white;
        border-bottom-right-radius: 4px;
    }

    .received .message-bubble {
        background: var(--bg-body);
        color: var(--text-primary);
        border-bottom-left-radius: 4px;
        border: 1px solid var(--border);
    }

    .msg-time {
        display: block;
        font-size: 0.65rem;
        margin-top: 0.3rem;
        opacity: 0.7;
        text-align: right;
    }

    .chat-input-area {
        padding: 1.5rem;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 1rem;
        background: var(--bg-body);
    }

    .chat-input-area textarea {
        flex: 1;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 0.8rem 1.2rem;
        resize: none;
        height: 50px;
        font-family: inherit;
        font-size: 0.95rem;
        background: var(--bg-surface);
        color: var(--text-primary);
    }

    .chat-input-area textarea:focus {
        outline: none;
        border-color: var(--primary);
    }

    #send-btn {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: var(--primary);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s;
    }

    #send-btn:hover {
        transform: scale(1.05);
    }

    .order-badge {
        font-size: 0.7rem;
        background: var(--bg-hover);
        color: var(--primary);
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 600;
        margin-right: 4px;
    }

    .unread-badge {
        background: var(--primary);
        color: white;
        font-size: 0.7rem;
        font-weight: 700;
        min-width: 18px;
        height: 18px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
    }
</style>

@endsection

@section('scripts')
<script>
    const container = document.getElementById('messages-container');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('message-body');
    const convId = {{ $conversation->id }};
    const currentUserId = {{ auth()->id() }};

    // Scroll to bottom on load
    container.scrollTop = container.scrollHeight;

    // Handle AJAX form submission
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const body = input.value.trim();
        if (!body) return;

        input.value = '';
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ body })
            });

            if (response.ok) {
                const data = await response.json();
                appendMessage(data.message);
                container.scrollTop = container.scrollHeight;
            }
        } catch (error) {
            console.error('Error sending message:', error);
        }
    });

    function appendMessage(msg) {
        const isSent = msg.sender_id === currentUserId;
        const html = `
            <div class="message-wrapper ${isSent ? 'sent' : 'received'}">
                <div class="message-bubble">
                    ${msg.body}
                    <span class="msg-time">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    // Polling for new messages
    let lastMsgCount = {{ count($messages) }};
    async function pollMessages() {
        try {
            const response = await fetch(`/chat/${convId}/messages`);
            if (response.ok) {
                const messages = await response.json();
                if (messages.length > lastMsgCount) {
                    container.innerHTML = '';
                    messages.forEach(appendMessage);
                    lastMsgCount = messages.length;
                    container.scrollTop = container.scrollHeight;
                }
            }
        } catch (error) {
            console.error('Error polling messages:', error);
        }
    }

    setInterval(pollMessages, 3000); // Poll every 3 seconds
</script>
@endsection
