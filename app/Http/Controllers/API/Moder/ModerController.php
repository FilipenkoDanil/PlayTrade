<?php

namespace App\Http\Controllers\API\Moder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\FindChatRequest;
use App\Http\Requests\Chat\ModerFindChatRequest;
use App\Http\Requests\Deal\DisputeDealRequest;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Models\Chat;
use App\Models\Deal;
use App\Services\ChatService;
use App\Services\MessageService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModerController extends Controller
{
    public function __construct(private readonly ChatService $chatService, private readonly MessageService $messageService, private readonly TransactionService $transactionService)
    {
    }

    public function showChat(Chat $chat)
    {
        return $this->chatService->getChatMessages($chat);
    }

    public function findChat(ModerFindChatRequest $request)
    {
        return $this->chatService->createOrGetChat($request->userFirst, $request->userSecond);
    }

    public function sendMessage(StoreMessageRequest $request)
    {
        return $this->messageService->sendMessage($request->message, $request->chat_id, Auth::id(), 'moder');
    }

}
