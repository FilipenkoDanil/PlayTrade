<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageService
{
    public function sendMessage(string $message, int $chat_id, User $user)
    {
        $chat = Chat::findOrFail($chat_id);

        return DB::transaction(function () use ($message, $chat, $user) {
            $message = $chat->messages()->create([
                'message' => $message,
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
