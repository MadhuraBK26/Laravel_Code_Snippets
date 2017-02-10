<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissionGroupIdToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function(Blueprint $table) {
            $table->integer('permission_group_id')->unsigned()->index();
            $table->foreign('permission_group_id')->references('id')->on('permission_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function(Blueprint $table) {
            $table->dropForeign('permissions_permission_group_id_foreign');
            $table->dropColumn('permission_group_id');
        });
    }
}
