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
            $table->increments('id')->unsigned();
            $table->string('email')->unique();
            $table->string('name')->unique();
            $table->integer('role_id')->unsigned();
            $table->string('fname')->nullable();
            $table->string('sname')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            //$table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::table('users', function (Blueprint $table){
            $table->unsignedInteger('phone')->nullable();
            $table->string('address', 255)->nullable();
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
