<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeedbackMetricsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_metrics',
            function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('metrics')->unique();
            $table->integer('metrics_rating');
            $table->integer('category_id')->unsigned();
            $table->integer('sort');
            $table->boolean('status');
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
        Schema::drop('feedback_metrics');
    }
}