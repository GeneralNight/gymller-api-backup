<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableClientGymTrainingSheetExercises extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_gym_training_sheet_exercises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("training_sheet_id");
            $table->unsignedBigInteger("exercise_id");
            $table->integer("series");
            $table->integer("repetitions");
            $table->timestamps();

            $table->foreign("training_sheet_id")->references("id")->on("client_gym_training_sheets");
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
        Schema::dropIfExists('client_gym_training_sheet_exercises');
    }
}
