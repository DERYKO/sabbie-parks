<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubLocalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_localities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parking_spot_id')->unsigned();
            $table->string('formatted_address');
            $table->timestamps();
            $table->foreign('parking_spot_id')
                ->references('id')
                ->on('parking_spots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_localities');
    }
}
