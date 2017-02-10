<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('roles')->delete();
        
		\DB::table('roles')->insert(array (
			0 => 
			array (
				'id' => '1',
				'name' => 'Delivery Head',
				'created_at' => '2015-10-20 00:00:00',
				'updated_at' => '2015-10-20 00:00:00',
				'slug' => 'delivery-head',
				'description' => NULL,
				'level' => '1',
			),
			1 => 
			array (
				'id' => '2',
				'name' => 'Practice Lead',
				'created_at' => '2015-10-20 00:00:00',
				'updated_at' => '2015-10-20 00:00:00',
				'slug' => 'practice-lead',
				'description' => NULL,
				'level' => '2',
			),
			2 => 
			array (
				'id' => '3',
				'name' => 'Project Manager',
				'created_at' => '2015-10-20 00:00:00',
				'updated_at' => '2015-10-20 00:00:00',
				'slug' => 'project-manager',
				'description' => NULL,
				'level' => '3',
			),
			3 => 
			array (
				'id' => '4',
				'name' => 'People',
				'created_at' => '2015-10-20 00:00:00',
				'updated_at' => '2015-10-20 00:00:00',
				'slug' => 'people',
				'description' => NULL,
				'level' => '4',
			),
			4 => 
			array (
				'id' => '5',
				'name' => 'Admin',
				'created_at' => '2015-10-20 00:00:00',
				'updated_at' => '2015-10-20 00:00:00',
				'slug' => 'admin',
				'description' => NULL,
				'level' => '5',
			),
		));
	}

}
