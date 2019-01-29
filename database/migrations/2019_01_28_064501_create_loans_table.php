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
            $table->integer('salary_id')->nullable()->default(null);
            $table->string('loan_name')->nullable()->default('');
            $table->float('amount',200,10)->nullable()->default(0);//could be a large value
            $table->date('start_date')->nullable();//date the loan installment started
            $table->date('end_date')->nullable();//date the loan installment ends
            $table->integer('installments')->nullable();//installment count
            $table->string('loan_type')->nullable()->default('');//type of loan, option left for many different types
            $table->float('interest',5,2)->nullable()->default(0);
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
