<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('logo')->nullable();
            $table->integer('region_id')->unsigned()->nullable();
            $table->integer('location_id')->unsigned()->nullable();
            $table->string('address')->nullable();
            $table->string('telephone_no')->nullable();
            $table->string('telephone_no_1')->nullable();
            $table->string('email_address');
            $table->string('website')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('postal_code')->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->timestamps();
            $table->foreign('region_id')
                ->references('id')
                ->on('regions');
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
        Schema::dropIfExists('clients');
    }
}
