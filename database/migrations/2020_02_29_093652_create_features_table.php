<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parking_spot_id')->unsigned();
            $table->integer('security_id')->unsigned();
            $table->timestamps();
            $table->foreign('parking_spot_id')
                ->references('id')
                ->on('parking_spots');
            $table->foreign('security_id')
                ->references('id')
                ->on('securities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('features');
    }
}
