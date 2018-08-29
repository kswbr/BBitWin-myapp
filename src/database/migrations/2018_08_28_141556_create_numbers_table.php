<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serial_numbers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('player_id')->unsigned()->nullable();
            $table->integer('number')->unsigned();
            $table->integer('serial_id')->unsigned()->nullable();
            $table->foreign('serial_id')->references('id')->on('campaign_serials')->onDelete("set null");
            $table->timestamps();
            $table->unique(['serial_id', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('serial_numbers');
    }
}
