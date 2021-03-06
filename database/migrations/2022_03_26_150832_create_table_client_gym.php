<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableClientGym extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_gyms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("gym_id");
            $table->unsignedBigInteger("client_id");
            $table->boolean("status");
            $table->timestamps();

            $table->foreign("gym_id")->references("id")->on("gyms");
            $table->foreign("client_id")->references("id")->on("clients");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_gyms');
    }
}
