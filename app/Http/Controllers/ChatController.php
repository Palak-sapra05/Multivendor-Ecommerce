<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Store;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $conversations = Conversation::where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->with(['buyer', 'seller', 'order'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('chat.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        // Ensure user is part of the conversation
        if ($conversation->buyer_id !== Auth::id() && $conversation->seller_id !== Auth::id()) {
            abort(403);
        }

        $messages = $conversation->messages()->with('sender')->orderBy('created_at', 'asc')->get();

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chat.show', compact('conversation', 'messages'));
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        if ($conversation->buyer_id !== Auth::id() && $conversation->seller_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'body' => 'required|string',
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => Auth::id(),
            'body' => $request->body,
        ]);

        $conversation->update(['last_message_at' => now()]);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $message->load('sender'),
            ]);
        }

        return back();
    }

    public function start(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:users,id',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        $buyerId = Auth::id();
        $sellerId = $request->seller_id;
        $orderId = $request->order_id;

        if ($buyerId == $sellerId) {
            return back()->with('error', 'You cannot chat with yourself.');
        }

        // Check if conversation already exists
        $conversation = Conversation::where('buyer_id', $buyerId)
            ->where('seller_id', $sellerId)
            ->where('order_id', $orderId)
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'buyer_id' => $buyerId,
                'seller_id' => $sellerId,
                'order_id' => $orderId,
                'last_message_at' => now(),
            ]);
        }

        return redirect()->route('chat.show', $conversation->id);
    }

    public function getMessages(Conversation $conversation)
    {
        if ($conversation->buyer_id !== Auth::id() && $conversation->seller_id !== Auth::id()) {
            abort(403);
        }

        $messages = $conversation->messages()->with('sender')->orderBy('created_at', 'asc')->get();

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }
}
