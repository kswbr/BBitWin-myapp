<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_campaign_counts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('player_id')->unsigned()->nullable();
            $table->char('campaign_code',100)->nullable();
            $table->foreign('campaign_code')->references('code')->on('campaigns')->onDelete("set null");
            $table->integer('days_count')->unsigned()->default(0);
            $table->integer('continuous_days_count')->unsigned()->default(0);
            $table->dateTime('check_date');
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
        Schema::dropIfExists('player_campaign_counts');
    }
}
