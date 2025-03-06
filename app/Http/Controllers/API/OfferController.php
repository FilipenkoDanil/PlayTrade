<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Offer\StoreOfferRequest;
use App\Http\Requests\Offer\UpdateOfferRequest;
use App\Http\Resources\OfferResource;
use App\Http\Resources\RatingResource;
use App\Models\Offer;
use App\Services\OfferService;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    public function __construct(private readonly OfferService $offerService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return OfferResource::collection(Auth::user()->offers()->with(['category.game', 'category.unit'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfferRequest $request)
    {
        return new OfferResource($this->offerService->create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Offer $offer)
    {
        return (new OfferResource($offer->load(['attributes', 'category.unit', 'server', 'seller'])))->additional([RatingResource::collection($offer->seller->ratings())]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferRequest $request, Offer $offer)
    {
        return new OfferResource($this->offerService->update($offer, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();

        return response()->json([
            'message' => 'Offer deleted'
        ]);
    }
}
