<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPercentageInvolvedInProjectmanagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_manager', function (Blueprint $table) {
            $table->integer('percentage_involved')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_manager', function (Blueprint $table) {
            $table->dropColumn('percentage_involved');
        });
    }
}
