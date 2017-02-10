<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissionBasicTypeIdToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->integer('permission_basic_type_id')->unsigned()->nullable();
            $table->foreign('permission_basic_type_id')->references('id')->on('permission_basic_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign('permissions_permission_basic_type_id_foreign');
            $table->dropColumn('permission_basic_type_id');
        });
    }
}
