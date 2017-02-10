<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesignationsFeedbackMetricsPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designation_feedback_metric', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('metrics_id')->unsigned();
            $table->integer('navigator_designation_id')->unsigned();
            $table->boolean('is_mandatory');
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
        Schema::drop('designation_feedback_metric');
    }
}
