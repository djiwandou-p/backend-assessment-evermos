<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\Player;
use Illuminate\Http\Request;
use App\Http\Resources\ContainerResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController as ApiController;

class ContainerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Player $player)
    {
        $models = Container::where('player_id', $player->id)->get();
        return $this->sendResponse(["total" => $models->count(), "data" => ContainerResource::collection($models)], 'OK');
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
            'capacity' => 'required|integer|max:20',
        ]);
        if ($validator->fails()) {
            return $this->sendResponse($validator->errors(), 'Validation Error', 400, false);
        }
        $data['player_id'] = $player->id;
        $model = Container::create($data);
        return $this->sendResponse(new ContainerResource($model), 'Created succesfully.', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function show(Player $player, Container $container)
    {
        return $this->sendResponse(new ContainerResource($container), 'OK');
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
        return $this->sendResponse(new ContainerResource($container), 'Updated successfully.');
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
            $container->ammount--;
            $player->state = "READY";
            $player->update();
            return $this->sendResponse(new ContainerResource($container), 'The Container is fully loaded with tennis balls and you\'re ready to play.', 403, false);
        }
        $container->update();
        return $this->sendResponse(new ContainerResource($container), 'OK');
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
        return $this->sendResponse(new ContainerResource($container), 'Deleted successfully.', 204);
    }
}
