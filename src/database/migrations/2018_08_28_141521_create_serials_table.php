<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_serials', function (Blueprint $table) {
            $table->increments('id');
            $table->char('project',100);
            $table->string('name');
            $table->char('campaign_code',100)->unique()->nullable();
            $table->integer('total');
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
        Schema::dropIfExists('campaign_serials');
    }
}
