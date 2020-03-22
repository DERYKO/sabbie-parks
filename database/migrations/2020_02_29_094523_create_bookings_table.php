<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('parking_spot_id')->unsigned();
            $table->integer('expiry_time');
            $table->string('registration_number')->nullable();
            $table->string('color')->nullable();
            $table->integer('user_vehicle_id')->unsigned()->nullable();
            $table->float('inconvenience_fee')->nullable();
            $table->dateTime('scheduled_book_time')->nullable();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->foreign('parking_spot_id')
                ->references('id')
                ->on('parking_spots');
            $table->foreign('user_vehicle_id')
                ->references('id')
                ->on('user_vehicles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
