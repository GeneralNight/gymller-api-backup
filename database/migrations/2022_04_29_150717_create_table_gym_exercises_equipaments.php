<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableGymExercisesEquipaments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_exercises_equipaments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gym_id');
            $table->unsignedBigInteger('equipament_id');
            $table->unsignedBigInteger('exercise_id');
            $table->timestamps();

            $table->foreign("gym_id")->references("id")->on("gyms");
            $table->foreign("equipament_id")->references("id")->on("gym_equipaments");
            $table->foreign("exercise_id")->references("id")->on("gym_exercises");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gym_exercises_equipaments');
    }
}
