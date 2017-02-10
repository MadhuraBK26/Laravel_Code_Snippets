<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PersonalDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('navigator_personal', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('father_name')->nullable();
            $table->enum('marital_status', array('single', 'married', 'divorcee'));
            $table->date('date_of_birth')->nullable();
            $table->text('residential_address')->nullable();
            $table->text('present_address')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('landline')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('pan_number')->nullable();
            $table->text('aadhar_number')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('navigator_personal');
    }
}
