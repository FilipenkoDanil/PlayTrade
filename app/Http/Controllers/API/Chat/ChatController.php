<?php

namespace App\Http\Controllers\API\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\StoreChatRequest;
use App\Models\Chat;
use App\Services\ChatService;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct(private readonly ChatService $chatService)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->chatService->getUserChats(Auth::user()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatRequest $request)
    {
        $user = Auth::user();
        $otherUserId = $request->user_id;

        $chat = $this->chatService->createOrGetChat($otherUserId, $user->id);

        return response()->json([
            'chat' => $chat,
        ], $chat->wasRecentlyCreated ? 201 : 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        return $this->chatService->getChatMessages($chat, Auth::id());
    }
}
