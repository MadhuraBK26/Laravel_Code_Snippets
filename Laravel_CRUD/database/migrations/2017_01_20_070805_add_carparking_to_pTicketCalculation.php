<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCarparkingToPTicketCalculation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('pTicketCalculation', function (Blueprint $table) {
           // $table->increments('id');
           // $table->string('name',40)->change();
            //$table->string('carNumber')->unique();
            //$table->string('carModel');
           /* $table->integer('farePerDay');
            $table->integer('noOfDays');*/
             $table->string('noOfCars')->change(); 
           // $table->rememberToken();
           // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         $table->integer('noOfCars')->change();
    }
}
