<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDailyIncrementAndTimeToLotteriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lotteries', function (Blueprint $table) {
             $table->integer('daily_increment')->default(0);
             $table->integer('daily_increment_time')->default(0);
             $table->dateTime('run_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lotteries', function (Blueprint $table) {
            //
            $table->dropColumn('daily_increment');
            $table->dropColumn('daily_increment_time');
            $table->dropColumn('run_time');
        });
    }
}
