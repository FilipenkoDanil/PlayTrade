<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deal\StoreDealRequest;
use App\Http\Resources\DealResource;
use App\Models\Deal;
use App\Services\ChatService;
use App\Services\DealService;
use App\Services\MessageService;
use Illuminate\Support\Facades\Auth;


class DealController extends Controller
{
    public function __construct(private readonly DealService $dealService, private readonly ChatService $chatService, private readonly MessageService $messageService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DealResource::collection(Auth::user()->allDeals());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDealRequest $request)
    {
        $result = $this->dealService->create($request->validated());

        if (is_string($result)) {
            return response()->json(['error' => $result], 400);
        }

        $chat = $this->chatService->createOrGetChat(Auth::id(), $result->offer->seller->id);
        $message = "Покупатель  оплатил заказ #$result->id. $result->offer_game, $result->offer_category. $result->quantity$result->offer_unit . не забудьте потом нажать кнопку «Подтвердить выполнение заказа».";
        $this->messageService->sendMessage($message, $chat->id, Auth::user());

        return new DealResource($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Deal $deal)
    {
        return new DealResource($deal);
    }

    public function confirm(Deal $deal)
    {
        if (Auth::id() !== $deal->buyer_id) {
            return response()->json(['error' => 'Only the buyer can confirm this deal.'], 403);
        }

        $this->dealService->confirm($deal);

        return response()->json(['message' => 'Deal confirmed']);
    }

    public function cancel(Deal $deal)
    {
        if (Auth::id() !== $deal->offer->seller_id) {
            return response()->json(['error' => 'Only the seller can cancel this deal.'], 403);
        }

        $this->dealService->cancel($deal);

        return response()->json(['message' => 'Deal cancelled']);
    }

    public function orders()
    {
        return DealResource::collection(Auth::user()->buyerDeals()->get());
    }

    public function sales()
    {
        return DealResource::collection(Auth::user()->sellerDeals()->get());
    }
}
