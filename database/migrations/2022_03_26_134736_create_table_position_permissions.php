<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePositionPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('position_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("position_id");
            $table->unsignedBigInteger("permission_id");
            $table->timestamps();

            $table->foreign("position_id")->references("id")->on("gym_positions");
            $table->foreign("permission_id")->references("id")->on("permissions");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('position_permissions');
    }
}
