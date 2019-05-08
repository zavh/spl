<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salary_id')->unsigned();
            $table->string('loan_name')->nullable()->default('');
            $table->float('amount',200,2);//could be a large value
            $table->date('start_date');//date the loan installment started
            $table->date('end_date');//date the loan installment ends
            $table->integer('tenure');//installment count
            $table->string('loan_type');//type of loan, option left for many different types
            $table->float('interest',5,2)->nullable()->default(0);
            $table->json('schedule');
            $table->json('stickyness');
            $table->tinyInteger('active')->default(1);
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
        Schema::dropIfExists('loans');
    }
}
