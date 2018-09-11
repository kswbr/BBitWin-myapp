<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOpenCloseDateToSerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('serials', function (Blueprint $table) {
            $table->boolean('active')->default(false);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('serials', function (Blueprint $table) {
            $table->dropColumn('active');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            //
        });
    }
}
