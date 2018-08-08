<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('project');
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('role')->default(0);
            $table->boolean('allow_over_project')->default(false);
            $table->boolean('allow_campaign')->default(true);
            $table->boolean('allow_vote')->default(true);
            $table->boolean('allow_user')->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
