<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllowedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alloweds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parking_spot_id')->unsigned();
            $table->integer('vehicle_type_id')->unsigned();
            $table->timestamps();
            $table->foreign('parking_spot_id')
                ->references('id')
                ->on('parking_spots');
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
        Schema::dropIfExists('alloweds');
    }
}
