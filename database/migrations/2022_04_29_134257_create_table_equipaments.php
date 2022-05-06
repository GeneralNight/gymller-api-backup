<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEquipaments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_equipaments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("gym_id");
            $table->string("name");
            $table->integer("number");
            $table->boolean("status");
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
        Schema::dropIfExists('gym_equipaments');
    }
}
