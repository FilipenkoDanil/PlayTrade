<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deal\StoreDealRequest;
use App\Http\Requests\Deal\UpdateDealRequest;
use App\Http\Resources\DealResource;
use App\Models\Deal;

class DealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DealResource::collection(Deal::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDealRequest $request)
    {
        return new DealResource(Deal::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Deal $deal)
    {
        return new DealResource($deal);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDealRequest $request, Deal $deal)
    {
        $deal->update($request->all());

        return new DealResource($deal);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deal $deal)
    {
        $deal->delete();

        return response()->json([
            'message' => 'Deal deleted'
        ]);
    }
}
