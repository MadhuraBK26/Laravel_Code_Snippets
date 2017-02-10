<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
      public function up()
    {
         Schema::create('feedback_transaction' , function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('people_feedback_id')->unsigned();
            $table->integer('feedback_metrics_id')->unsigned();
            $table->integer('expectation_id')->unsigned()->nullable(); 
            $table->string('comments')->nullable();
            $table->boolean('status');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            $table->foreign('people_feedback_id')
                  ->references('id')
                  ->on('people_feedback')
                  ->onDelete('cascade');

            $table->foreign('feedback_metrics_id')
                  ->references('id')
                  ->on('feedback_metrics')
                  ->onDelete('cascade');

            $table->foreign('expectation_id')
                  ->references('id')
                  ->on('expectation')
                  ->onDelete('cascade');
              });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('feedback_transaction');
    }
}
