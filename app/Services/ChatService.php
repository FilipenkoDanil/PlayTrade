<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ChatService
{
    public function createOrGetChat(int $userFirstId, int $userSecondId): Chat
    {
        return DB::transaction(function () use ($userFirstId, $userSecondId) {
            $existingChat = Chat::with('users')->whereHas('users', function ($query) use ($userSecondId) {
                $query->where('users.id', $userSecondId);
            })
                ->whereHas('users', function ($query) use ($userFirstId) {
                    $query->where('users.id', $userFirstId);
                })
                ->first();

            if ($existingChat) {
                return $existingChat;
            }

            $chat = Chat::create();
            $chat->users()->attach([$userFirstId, $userSecondId]);

            return $chat;
        });
    }

    public function getUserChats(User $user)
    {
        return $user->chats()
            ->with([
                'users',
                'lastMessage.user'
            ])
            ->get()
            ->map(function ($chat) use ($user) {
                $companion = $chat->users->where('id', '!=', $user->id)->first();

                $pivot = $chat->users->where('id', $user->id)->first()->pivot;

                return [
                    'id' => $chat->id,
                    'unread_count' => $pivot ? $pivot->unread_count : 0,
                    'companion' => $companion ? [
                        'id' => $companion->id,
                        'name' => $companion->name,
                    ] : null,
                    'last_message' => $chat->lastMessage ? [
                        'text' => $chat->lastMessage->message,
                        'date' => $chat->lastMessage->created_at->toDateTimeString(),
                    ] : null
                ];
            })->sortByDesc(function ($chat) {
                return $chat['last_message']['date'] ?? null;
            })->values();
    }

    public function getChatMessages(Chat $chat, int $userId): Collection
    {
        $userPivot = $chat->users()->where('user_id', '=', $userId)->first()->pivot;
        $userPivot->unread_count = 0;
        $userPivot->save();

        return $chat->messages()->with('user')->get();
    }
}
