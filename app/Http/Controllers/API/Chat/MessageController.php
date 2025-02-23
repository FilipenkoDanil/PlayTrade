<?php

namespace App\Http\Controllers\API\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreMessageRequest;
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
        return $this->messageService->sendMessage($request, Auth::user());
    }

}
