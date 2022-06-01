<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableClientGymTrainingSheet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_gym_training_sheets', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger("client_gym_id");
            $table->boolean("status");
            $table->timestamps();

            $table->foreign("client_gym_id")->references("id")->on("client_gyms");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_gym_training_sheets');
    }
}
