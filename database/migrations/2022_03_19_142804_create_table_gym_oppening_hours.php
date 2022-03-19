<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableGymOppeningHours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_oppening_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("gym_id");
            $table->time("openning_hour");
            $table->time("closing_hour");
            $table->string("week_day");
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
        Schema::dropIfExists('gym_oppening_hours');
    }
}
