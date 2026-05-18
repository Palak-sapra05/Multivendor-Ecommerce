@extends('layouts.app')

@section('content')
<div class="chat-page-container">
    <div class="chat-sidebar">
        <div class="sidebar-header">
            <h2>Messages</h2>
        </div>
        <div class="conversation-list">
            @forelse($conversations as $conv)
                @php
                    $otherUser = (auth()->id() === $conv->buyer_id) ? $conv->seller : $conv->buyer;
                    $unreadCount = $conv->messages()->where('sender_id', '!=', auth()->id())->where('is_read', false)->count();
                @endphp
                <a href="{{ route('chat.show', $conv->id) }}" class="conversation-item {{ isset($conversation) && $conversation->id === $conv->id ? 'active' : '' }}">
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
            @empty
                <div class="empty-state">
                    <p>No conversations yet.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="chat-main empty">
        <div class="select-prompt">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <h3>Select a conversation</h3>
            <p>Choose a chat from the sidebar to start messaging</p>
        </div>
    </div>
</div>

<style>
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
        background: rgba(var(--primary-rgb), 0.1);
        border-left: 4px solid var(--primary);
    }

    .avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .conv-info {
        flex: 1;
        min-width: 0;
    }

    .conv-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 0.25rem;
    }

    .user-name {
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .time {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .conv-preview {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
    }

    .last-msg {
        font-size: 0.875rem;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .order-badge {
        font-size: 0.7rem;
        background: rgba(var(--primary-rgb), 0.1);
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

    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: var(--bg-surface);
    }

    .chat-main.empty {
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        text-align: center;
    }

    .select-prompt svg {
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .select-prompt h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }
</style>
@endsection
