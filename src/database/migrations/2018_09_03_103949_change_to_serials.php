<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeToSerials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('serial_numbers');
        Schema::dropIfExists('campaign_serials');
        Schema::create('serials', function (Blueprint $table) {
            $table->increments('id');
            $table->char('project',100);
            $table->string('name');
            $table->char('code',100)->unique()->nullable();
            $table->integer('total');
            $table->integer('winner_total');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('serials');
    }
}
