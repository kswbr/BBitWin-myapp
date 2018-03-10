<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('screen_name');
            $table->string('name');
            $table->string('project');
            $table->string('password');
            $table->boolean('has_user_management')->default(false);
            $table->boolean('has_create_delete_permission')->default(false);
            $table->unique(['name', 'project']);
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
        Schema::dropIfExists('admins');
    }
}
