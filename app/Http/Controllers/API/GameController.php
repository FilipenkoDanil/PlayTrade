<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\StoreGameRequest;
use App\Http\Requests\Game\UpdateGameRequest;
use App\Http\Resources\GameResource;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Game;
use App\Models\Server;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return GameResource::collection(Game::with('categories')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGameRequest $request)
    {
        $game = DB::transaction(function () use ($request) {
            $game = Game::create([
                'title' => $request->title
            ]);

            foreach ($request->categories as $category) {
                Category::create([
                    'title' => $category['title'],
                    'unit_id' => $category['unit_id'],
                    'game_id' => $game->id
                ]);
            }

            foreach ($request->servers as $server) {
                Server::create([
                    'title' => $server['title'],
                    'game_id' => $game->id
                ]);
            }

            foreach ($request['attributes'] as $attribute) {
                Attribute::create([
                    'title' => $attribute['title'],
                    'game_id' => $game->id
                ]);
            }
            return $game;
        });

        return new GameResource($game);
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        return new GameResource($game->load(['categories', 'attributes', 'servers']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGameRequest $request, Game $game)
    {
        $game->update($request->validated());

        return new GameResource($game);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        DB::transaction(function () use ($game) {
            $game->categories()->delete();
            $game->servers()->delete();
            $game->delete();
        });

        return response()->json(
            ['message' => 'Game deleted']
        );
    }

    public function trashed()
    {
        return GameResource::collection(Game::onlyTrashed()->get());
    }
}
