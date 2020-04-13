<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned()->nullable();
            $table->integer('parking_spot_id')->unsigned()->nullable();
            $table->string('payment_type');
            $table->string('merchantRequestId')->nullable();
            $table->string('checkoutRequestId')->nullable();
            $table->float('amount');
            $table->float('balance')->nullable();
            $table->string('partyA')->nullable();
            $table->string('partyB')->nullable();
            $table->boolean('status');
            $table->string('receipt_no')->nullable();
            $table->timestamps();
            $table->foreign('client_id')
                ->references('id')
                ->on('clients');
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
        Schema::dropIfExists('collections');
    }
}
