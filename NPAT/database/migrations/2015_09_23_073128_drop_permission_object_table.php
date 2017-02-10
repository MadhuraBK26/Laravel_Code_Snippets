<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPermissionObjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::drop('permission_object');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('permission_object', function(Blueprint $table) {
            $table->increments('id');
            $table->text('model_name')->nullable();
            $table->integer('primary_id')->nullable();
            $table->timestamps();
        });
    }
}
