<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalarystructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salarystructures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('structurename')->nullable()->default('');
            $table->float('houserent',6,2)->nullable()->default(0.0);
            $table->float('medicalallowance',6,2)->nullable()->default(0.0);
            $table->float('conveyance',6,2)->nullable()->default(0.0);
            $table->float('providentfundcompany',6,2)->nullable()->default(0.0);
            $table->float('providentfundself',6,2)->nullable()->default(0.0);
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
        Schema::dropIfExists('salarystructures');
    }
}
