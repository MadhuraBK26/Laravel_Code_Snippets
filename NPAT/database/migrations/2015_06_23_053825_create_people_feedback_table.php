<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people_feedback' , function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->integer('manager_id')->unsigned();
            $table->integer('people_id')->unsigned();
            $table->boolean('status');
            $table->date('start_date');
            $table->date('end_date');

//            $table->foreign('project_id')
//                  ->references('id')
//                  ->on('project')
//                  ->onDelete('cascade');

//            $table->foreign('manager_id')
//                  ->references('id')
//                  ->on('users')
//                  ->onDelete('cascade');

            $table->foreign('people_id')
                  ->references('id')
                  ->on('users')
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
        Schema::drop('people_feedback');
    }
}
