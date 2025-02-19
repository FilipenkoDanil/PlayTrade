<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Server\StoreServerRequest;
use App\Http\Requests\Server\UpdateServerRequest;
use App\Http\Resources\ServerResource;
use App\Models\Server;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ServerResource::collection(Server::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServerRequest $request)
    {
        return new ServerResource(Server::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Server $server)
    {
        return new ServerResource($server->load('game'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServerRequest $request, Server $server)
    {
        $server->update($request->validated());

        return new ServerResource($server);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Server $server)
    {
        $server->delete();

        return response()->json([
            'message' => 'Server deleted'
        ]);
    }
}
