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
            $table->json('contacts');
            $table->integer('user_id');
            $table->date('start_date');
            $table->date('deadline');
            $table->unsignedInteger('department_id');
            $table->integer('report_id')->unsigned()->nullable()->default(0);
            $table->tinyInteger('status')->default(0);
            $table->integer('allocation')->default(0);
            $table->tinyInteger('completed')->unsigned()->default(0);
            $table->string('ref')->nullable();
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
