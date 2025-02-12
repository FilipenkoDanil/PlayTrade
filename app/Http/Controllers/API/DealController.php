<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deal\StoreDealRequest;
use App\Http\Resources\DealResource;
use App\Models\Deal;
use App\Services\DealService;
use Illuminate\Support\Facades\Auth;


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
        return DealResource::collection(Auth::user()->deals()->get());
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
}
