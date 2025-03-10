<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\Deal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageService
{
    public function sendMessage(string $message, int $chat_id, int $senderId, string $type = 'default')
    {
        $chat = Chat::findOrFail($chat_id);

        return DB::transaction(function () use ($message, $chat, $senderId, $type) {
            $message = $chat->messages()->create([
                'message' => $message,
                'user_id' => $senderId,
                'type' => $type
            ]);

            $companions = $chat->users->where('id', '!=', $senderId);
            foreach ($companions as $companion) {
                $this->incrementUnreadMessage($chat, $companion->id);
            }

            return $message->load('user');
        });
    }


    public function incrementUnreadMessage(Chat $chat, int $userId): void
    {
        $companionPivot = $chat->users->where('id', $userId)->first()->pivot;
        $companionPivot->unread_count += 1;
        $companionPivot->save();
    }

    public function getDealNotification(Deal $deal, string $type): string
    {
        return match ($type) {
            'paid' => "Покупатель оплатил заказ #$deal->id. $deal->offer_game, $deal->offer_category. $deal->quantity$deal->offer_unit.
            Не забудьте потом нажать кнопку «Подтвердить выполнение заказа».",
            'confirmed' => "Покупатель подтвердил успешное выполнение заказа #$deal->id и отправил деньги продавцу.",
            'canceled' => "Продавец отменил заказ #$deal->id. Сделка закрыта.",
            'disputed' => "Открыт спор по сделке #$deal->id. Ождиайте решение модератора.",
            'rating' => "Покупатель оставил отзыв к заказу #$deal->id.",
            'refundSeller' => "Модератор принял решение закрыть сделку #$deal->id в сторону продавца.",
            'refundBuyer' => "Модератор принял решение закрыть сделку #$deal->id в сторону покупателя.",
            'refundFiftyFifty' => "Модератор принял решение закрыть сделку #$deal->id с разделением средств между продавцом и покупателем поровну.",
            default => "Ошибка обработки типа."
        };
    }

    public function sendDealNotification(Deal $deal, Chat $chat, string $type): void
    {
        $message = $this->getDealNotification($deal, $type);
        $this->sendMessage($message, $chat->id, Auth::id(), 'notify');
    }
}
