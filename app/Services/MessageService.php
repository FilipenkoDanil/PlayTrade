<?php

namespace App\Services;

use App\Http\Requests\Message\StoreMessageRequest;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageService
{
    public function sendMessage(StoreMessageRequest $request, User $user)
    {
        $chat = Chat::findOrFail($request->chat_id);

        return DB::transaction(function () use ($request, $chat, $user) {
            $message = $chat->messages()->create([
                'message' => $request->message,
                'user_id' => Auth::id(),
            ]);

            $this->incrementUnreadMessage($chat, $user);

            return $message;
        });
    }


    public function incrementUnreadMessage(Chat $chat, User $user): void
    {
        $companionPivot = $chat->users->where('id', '!=', $user->id)->first()->pivot;
        $companionPivot->unread_count += 1;
        $companionPivot->save();
    }

}
