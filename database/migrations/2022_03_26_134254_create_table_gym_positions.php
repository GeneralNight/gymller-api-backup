<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableGymPositions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("gym_id");
            $table->string("name");
            $table->timestamps();

            $table->foreign("gym_id")->references("id")->on("gyms");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gym_positions');
    }
}
