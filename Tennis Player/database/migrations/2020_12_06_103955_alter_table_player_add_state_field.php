<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePlayerAddStateField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->enum('state', ['READY', 'NOT READY', 'PLAYED'])->nullable()->default('NOT READY')->after('name');
        });

        DB::update("UPDATE players LEFT JOIN (SELECT id, player_id FROM containers where ammount = capacity GROUP BY player_id, id) containers on players.id = containers.player_id SET players.state = IF(containers.id is NOT NULL, 'READY', 'NOT READY');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('state');           
        });
    }
}
