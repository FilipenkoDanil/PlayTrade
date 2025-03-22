<?php

namespace App\Http\Controllers\API;

use App\Events\SendMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rating\StoreRatingRequest;
use App\Http\Requests\Rating\UpdateRatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Deal;
use App\Models\Rating;
use App\Services\ChatService;
use App\Services\MessageService;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function __construct(private readonly ChatService $chatService, private readonly MessageService $messageService)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RatingResource::collection(Rating::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRatingRequest $request)
    {
        $rating = Rating::updateOrCreate(
            [
                'deal_id' => $request->deal_id,
                'user_id' => Auth::id()
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        $deal = Deal::find($request->deal_id);
        $chat = $this->chatService->createOrGetChat(Auth::id(), $deal->offer->seller->id);

        broadcast(new SendMessageEvent($this->messageService->sendDealNotification($deal, $chat, 'rating')));

        return new RatingResource($rating);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rating $rating)
    {
        return new RatingResource($rating);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRatingRequest $request, Rating $rating)
    {
        $rating->update($request->validated());

        return new RatingResource($rating);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating)
    {
        if ($rating->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $rating->delete();

        return response()->json([
            'message' => 'Rating deleted'
        ]);
    }
}
