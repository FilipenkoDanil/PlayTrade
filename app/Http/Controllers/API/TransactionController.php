<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TransactionResource::collection(Auth::user()->transactions()
            ->with(['transactable' => function ($query) {
                $query->with('status');
            }])
            ->orderBy('created_at', 'desc')->get());
    }
}
