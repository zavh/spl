<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_name');
            $table->integer('client_id');
            $table->integer('user_id');
            $table->integer('manager_id')->nullable();
            $table->dateTime('assigned')->nullable();
            $table->dateTime('deadline');
            $table->text('description');
<<<<<<< HEAD
            $table->tinyInteger('status');
            $table->tinyInteger('state')->nullable();
=======
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('state')->nullable();           
>>>>>>> 8cc324f9e7482648ce736a1dd246b6635502d2dd
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
        Schema::dropIfExists('projects');
    }
}
