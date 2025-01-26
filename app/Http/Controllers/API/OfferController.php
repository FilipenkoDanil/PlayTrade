<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Offer\StoreOfferRequest;
use App\Http\Requests\Offer\UpdateOfferRequest;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Services\OfferService;

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
        return OfferResource::collection(Offer::all());
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
        return new OfferResource($offer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferRequest $request, Offer $offer)
    {
        $offer->update($request->validated());

        return new OfferResource($offer);
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
