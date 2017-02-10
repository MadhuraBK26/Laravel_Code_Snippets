<?php

use Illuminate\Database\Seeder;

class PermissionBasicTypesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('permission_basic_types')->delete();
        
		\DB::table('permission_basic_types')->insert(array (
			0 => 
			array (
				'id' => '1',
				'name' => 'Create',
				'slug' => 'create',
				'created_at' => '2015-10-22 00:00:00',
				'updated_at' => '2015-10-22 00:00:00',
			),
			1 => 
			array (
				'id' => '2',
				'name' => 'Read',
				'slug' => 'read',
				'created_at' => '2015-10-14 00:00:00',
				'updated_at' => '2015-10-14 00:00:00',
			),
			2 => 
			array (
				'id' => '3',
				'name' => 'Update',
				'slug' => 'update',
				'created_at' => '2015-10-12 00:00:00',
				'updated_at' => '2015-10-30 00:00:00',
			),
			3 => 
			array (
				'id' => '4',
				'name' => 'Delete',
				'slug' => 'delete',
				'created_at' => '2015-10-21 00:00:00',
				'updated_at' => '2015-10-08 00:00:00',
			),
		));
	}

}
