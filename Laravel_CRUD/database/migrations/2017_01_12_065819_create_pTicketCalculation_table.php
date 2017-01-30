<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePTicketCalculationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('pTicketCalculation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('carNumber')->unique();
            $table->string('carModel');
            $table->integer('farePerDay');
            $table->integer('noOfDays');
            $table->integer('noOfCars');
           // $table->rememberToken();
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
        Schema::dropIfExists('pTicketCalculation');
    }
}
