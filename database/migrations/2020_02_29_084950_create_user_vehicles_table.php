<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('vehicle_type_id')->unsigned();
            $table->string('registration_no');
            $table->string('model_type');
            $table->string('color');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->foreign('vehicle_type_id')
                ->references('id')
                ->on('vehicle_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_vehicles');
    }
}
