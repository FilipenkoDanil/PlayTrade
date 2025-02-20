<?php

namespace App\Http\Controllers\API\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\StoreChatRequest;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $chats = $user->chats()
            ->with([
                'users' => function ($query) use ($user) {
                    $query->where('users.id', '!=', $user->id);
                },
                'lastMessage.user'
            ])
            ->get()
            ->map(function ($chat) use ($user) {
                return [
                    'id' => $chat->id,
                    'unread_count' => $chat->users->first()->pivot->unread_count ?? 0,
                    'companion' => $chat->users->first() ? [
                        'id' => $chat->users->first()->id,
                        'name' => $chat->users->first()->name,
                    ] : null,
                    'last_message' => $chat->lastMessage ? [
                        'text' => $chat->lastMessage->message,
                        'date' => $chat->lastMessage->created_at->toDateTimeString(),
                    ] : null
                ];
            });

        return response()->json($chats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatRequest $request)
    {
        $user = Auth::user();
        $otherUserId = $request->user_id;

        $existingChat = Chat::whereHas('users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
            ->whereHas('users', function ($query) use ($otherUserId) {
                $query->where('users.id', $otherUserId);
            })
            ->first();

        if ($existingChat) {
            return response()->json([
                'message' => 'Чат уже существует',
                'chat' => $existingChat,
            ], 200);
        }

        $chat = Chat::create();

        $chat->users()->attach([$user->id, $otherUserId]);

        return response()->json([
            'message' => 'Чат успешно создан',
            'chat' => $chat,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        return $chat->messages()->with('user')->get();
    }
}
