<?php

use App\Models\Deposit;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    return $user->chats()->where('chat_id', $chatId)->exists() || $user->hasRole('moder');
});

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return $user->id == $userId;
});

Broadcast::channel('deposit.{deposit_id}', function ($user, $depositId) {
    $deposit = Deposit::where('deposit_id', $depositId)->get()->first();

    if ($deposit && $deposit->user_id === $user->id) {
        return true;
    }

    return false;
});
