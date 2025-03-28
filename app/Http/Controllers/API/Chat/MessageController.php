<?php

namespace App\Http\Controllers\API\Chat;

use App\Events\SendMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Services\MessageService;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct(private readonly MessageService $messageService)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request)
    {
        $message = $this->messageService->sendMessage($request->message, $request->chat_id, Auth::id());

        broadcast(new SendMessageEvent($message))->toOthers();

        return MessageResource::make($message)->resolve();
    }

}
