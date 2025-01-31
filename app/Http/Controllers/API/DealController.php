<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deal\StoreDealRequest;
use App\Http\Requests\Deal\UpdateDealRequest;
use App\Http\Resources\DealResource;
use App\Models\Deal;
use App\Services\DealService;

class DealController extends Controller
{
    public function __construct(private readonly DealService $dealService)
    {
    }

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
        $result = $this->dealService->create($request->validated());

        if (is_string($result)) {
            return response()->json(['error' => $result], 400);
        }

        return new DealResource($result);
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
        $deal->update($request->validated());
        //Todo: изменить метод на вызов confirm | cancel
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
        //Todo: запретить удаление
    }
}
