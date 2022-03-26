<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableGymWorker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_worker', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("gym_id");
            $table->string("name");
            $table->string("cpf");
            $table->string("rg");
            $table->string("cep");
            $table->string("address");
            $table->string("neighborhood");
            $table->string("city");
            $table->string("state");
            $table->string("number");
            $table->string("email");
            $table->float("salary");
            $table->string("phone");
            $table->unsignedBigInteger("position_id");
            $table->string("username");
            $table->string("password");
            $table->timestamps();

            $table->foreign("gym_id")->references("id")->on("gyms");
            $table->foreign("position_id")->references("id")->on("gym_positions");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gym_worker');
    }
}
