<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deal\StoreDealRequest;
use App\Http\Resources\Deal\DealListResource;
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
        $this->messageService->sendDealNotification($result, $chat, 'paid');

        if ($result->offer->auto_message) {
            $this->messageService->sendMessage($result->offer->auto_message, $chat->id, $result->offer->seller_id);
        }

        return new DealResource($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Deal $deal)
    {
        return new DealListResource(
            $deal->load([
                'offer' => function ($query) {
                    $query->withTrashed()->with(['seller', 'category']);
                },
                'rating',
                'buyer'
            ])
        );
    }

    public function confirm(Deal $deal)
    {
        if (Auth::id() !== $deal->buyer_id) {
            return response()->json(['error' => 'Only the buyer can confirm this deal.'], 403);
        }

        $this->dealService->confirm($deal);

        $chat = $this->chatService->createOrGetChat(Auth::id(), $deal->offer->seller->id);
        $this->messageService->sendDealNotification($deal, $chat, 'confirmed');

        return response()->json(['message' => 'Deal confirmed']);
    }

    public function cancel(Deal $deal)
    {
        if (Auth::id() !== $deal->offer->seller_id) {
            return response()->json(['error' => 'Only the seller can cancel this deal.'], 403);
        }

        $this->dealService->cancel($deal);

        $chat = $this->chatService->createOrGetChat(Auth::id(), $deal->buyer_id);
        $this->messageService->sendDealNotification($deal, $chat, 'canceled');

        return response()->json(['message' => 'Deal cancelled']);
    }

    public function dispute(Deal $deal)
    {
        if (Auth::id() !== $deal->buyer_id && Auth::id() !== $deal->offer->seller_id) {
            return response()->json(['error' => 'Only the seller or buyer can dispute this deal.'], 403);
        }

        $this->dealService->dispute($deal);

        $chat = $this->chatService->createOrGetChat(Auth::id(), Auth::id() == $deal->buyer_id ? $deal->offer->seller_id : $deal->buyer_id);
        $this->messageService->sendDealNotification($deal, $chat, 'disputed');
    }

    public function orders()
    {
        return DealListResource::collection(Auth::user()->buyerDeals()->get());
    }

    public function sales()
    {
        return DealResource::collection(Auth::user()->sellerDeals()->get());
    }
}
