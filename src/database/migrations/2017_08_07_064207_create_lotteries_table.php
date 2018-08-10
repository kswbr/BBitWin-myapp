<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotteriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotteries', function (Blueprint $table) {
            $table->increments('id');
            $table->char('campaign_code',100)->nullable();
            $table->string('name');
            $table->char('code',100)->unique();
            $table->float('rate');
            $table->integer('total');
            $table->integer('limit');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('active');
            $table->integer('order');
            $table->timestamps();

            $table->foreign('campaign_code')->references('code')->on('campaigns')->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lotteries');
    }
}
