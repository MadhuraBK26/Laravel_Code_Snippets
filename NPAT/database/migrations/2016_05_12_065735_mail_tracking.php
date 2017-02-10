<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MailTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_tracking', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('from_mail')->unique();
            $table->string('to_mail')->unique();
            $table->integer('mail_counter')->unsigned();
            $table->string('mail_purpose')->nullable();
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
        Schema::drop('mail_tracking');
    }
}
