<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Container;
use Illuminate\Http\Request;
use App\Http\Resources\PlayerResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController as ApiController;

class PlayerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = Player::with('containers')->get();
        return $this->sendResponse(["total" => $models->count(), "data" => PlayerResource::collection($models)], 'OK');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|max:191',
        ]);
        if ($validator->fails()) {
            return $this->sendResponse($validator->errors(), 'Validation Error', 400, false);
        }
        $model = Player::create($data);
        return $this->sendResponse(new PlayerResource($model), 'Created succesfully.', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function show(Player $player)
    {
        return $this->sendResponse(new PlayerResource($player), 'OK');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Player $player)
    {
        $player->update($request->all());
        return $this->sendResponse(new PlayerResource($player), 'Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        $player->delete();
        return $this->sendResponse(new PlayerResource($player), 'Deleted successfully.', 204);
    }

    /**
     * Update the state resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function play(Request $request, Player $player)
    {
        $message = 'You\'re not ready to play.';
        $statusCode = 400;
        if($player->state == 'READY'){
            $model = Container::where(\DB::raw('ammount'), '=', \DB::raw('capacity'))->where('player_id', $player->id)->first();
            if(!empty($model)){
                $player->state = 'PLAYED';
                $player->update();
                $statusCode = 200;
                $message = 'You\'re played.';
            }else{
                $message = 'You don\'t have any container';
            }
        }else{
            if($player->state == 'PLAYED'){
                $message = 'You\'re in play.';
                $statusCode = 200;
            }
        }
        return $this->sendResponse(new PlayerResource($player), $message, $statusCode);
    }
}