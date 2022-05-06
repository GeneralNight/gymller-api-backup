<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableGymExercises extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_exercises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gym_id');
            $table->unsignedBigInteger('exercise_category_id');
            $table->string('name');
            $table->string('description');
            $table->boolean('status');
            $table->timestamps();

            $table->foreign("gym_id")->references("id")->on("gyms");
            $table->foreign("exercise_category_id")->references("id")->on("gym_exercises_category");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gym_exercises');
    }
}
