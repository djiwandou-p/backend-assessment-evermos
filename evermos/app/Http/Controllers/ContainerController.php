<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\Player;
use Illuminate\Http\Request;
use App\Http\Resources\ContainerResource;
use Illuminate\Support\Facades\Validator;

class ContainerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Player $player)
    {
        $containers = Container::where('player_id', $player->id)->get();
        return response([ 'data' => ContainerResource::collection($containers), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Player $player)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|max:191',
        ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $data['player_id'] = $player->id;
        $containers = Container::create($data);
        return response(['data' => new ContainerResource($containers), 'message' => 'Created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function show(Player $player, Container $container)
    {
        return response(['data' => new ContainerResource($container), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Player $player, Container $container)
    {
        $container->update($request->all());
        return response(['data' => new ContainerResource($container), 'message' => 'Update successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function updateAmmount(Request $request, Player $player, Container $container)
    {
        $container->ammount++;
        $validator = Validator::make($container->getAttributes(), [
            'ammount' => 'required|checkCapacity:'.$container->capacity,
        ]);
        if ($validator->fails()) {
            return response(['data' => new ContainerResource($container), 'message' => 'The Container is fully loaded with tennis balls and you\'re ready to play.'], 403);
        }
        $container->update();
        return response(['data' => new ContainerResource($container), 'message' => 'Update successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player, Container $container)
    {
        $container->delete();
        return response(['message' => 'Deleted']);
    }
}
