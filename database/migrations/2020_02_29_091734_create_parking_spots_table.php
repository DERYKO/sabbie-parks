<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkingSpotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parking_spots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('parking_spot_code')->nullable();
            $table->integer('client_id')->unsigned();
            $table->enum('status', ['booked', 'occupied', 'vacant', 'reserved']);
            $table->string('latitude');
            $table->string('longitude');
            $table->string('land_mark');
            $table->integer('location_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('location_id')
                ->references('id')
                ->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parking_spots');
    }
}
