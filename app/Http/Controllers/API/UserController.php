<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RatingResource;
use App\Http\Resources\User\UserProfileResource;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load([
            'activeOffers.category.game',
            'activeOffers.category.unit',
            'activeOffers.category.servers',
        ]);

        return (new UserProfileResource($user))->additional([RatingResource::collection($user->ratings())]);
    }
}
