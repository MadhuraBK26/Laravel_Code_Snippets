<?php

use Illuminate\Database\Seeder;

class PermissionRoutesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('permission_routes')->delete();
        
		\DB::table('permission_routes')->insert(array (
			0 => 
			array (
				'id' => 1,
				'route_name' => 'project',
				'route_url' => NULL,
				'permission_id' => 2,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			1 => 
			array (
				'id' => 2,
				'route_name' => 'project.create',
				'route_url' => NULL,
				'permission_id' => 3,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			2 => 
			array (
				'id' => 3,
				'route_name' => 'project.edit',
				'route_url' => NULL,
				'permission_id' => 4,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			3 => 
			array (
				'id' => 4,
				'route_name' => 'project.destroy',
				'route_url' => NULL,
				'permission_id' => 5,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			4 => 
			array (
				'id' => 5,
				'route_name' => 'project.show',
				'route_url' => NULL,
				'permission_id' => 38,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			5 => 
			array (
				'id' => 6,
				'route_name' => 'navigator',
				'route_url' => NULL,
				'permission_id' => 6,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			6 => 
			array (
				'id' => 7,
				'route_name' => 'admin.navigator.create',
				'route_url' => NULL,
				'permission_id' => 7,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			7 => 
			array (
				'id' => 8,
				'route_name' => 'admin.navigator.edit',
				'route_url' => NULL,
				'permission_id' => 8,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			8 => 
			array (
				'id' => 9,
				'route_name' => 'navigator.destroy',
				'route_url' => NULL,
				'permission_id' => 9,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			9 => 
			array (
				'id' => 10,
				'route_name' => 'admin.navigator.show',
				'route_url' => NULL,
				'permission_id' => 39,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			10 => 
			array (
				'id' => 11,
				'route_name' => 'register',
				'route_url' => NULL,
				'permission_id' => 10,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			11 => 
			array (
				'id' => 12,
				'route_name' => 'register.create ',
				'route_url' => NULL,
				'permission_id' => 11,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			12 => 
			array (
				'id' => 13,
				'route_name' => 'register.edit',
				'route_url' => NULL,
				'permission_id' => 12,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			13 => 
			array (
				'id' => 14,
				'route_name' => 'register.destroy ',
				'route_url' => NULL,
				'permission_id' => 13,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			14 => 
			array (
				'id' => 15,
				'route_name' => 'register.show ',
				'route_url' => NULL,
				'permission_id' => 40,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			15 => 
			array (
				'id' => 16,
				'route_name' => 'metrics ',
				'route_url' => NULL,
				'permission_id' => 14,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			16 => 
			array (
				'id' => 17,
				'route_name' => 'admin.metrics.create ',
				'route_url' => NULL,
				'permission_id' => 15,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			17 => 
			array (
				'id' => 18,
				'route_name' => 'admin.metrics.edit',
				'route_url' => NULL,
				'permission_id' => 16,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			18 => 
			array (
				'id' => 19,
				'route_name' => 'metrics.destroy ',
				'route_url' => NULL,
				'permission_id' => 17,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			19 => 
			array (
				'id' => 20,
				'route_name' => 'admin.metrics.show',
				'route_url' => NULL,
				'permission_id' => 41,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			20 => 
			array (
				'id' => 21,
				'route_name' => 'assign-metrics ',
				'route_url' => NULL,
				'permission_id' => 18,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			21 => 
			array (
				'id' => 22,
				'route_name' => 'admin.assign-metrics.create',
				'route_url' => NULL,
				'permission_id' => 19,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			22 => 
			array (
				'id' => 23,
				'route_name' => 'admin.assign-metrics.edit',
				'route_url' => NULL,
				'permission_id' => 20,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			23 => 
			array (
				'id' => 24,
				'route_name' => 'assign-metrics.destroy',
				'route_url' => NULL,
				'permission_id' => 21,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			24 => 
			array (
				'id' => 25,
				'route_name' => 'admin.assign-metrics.show',
				'route_url' => NULL,
				'permission_id' => 42,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			25 => 
			array (
				'id' => 26,
				'route_name' => 'metric-categories',
				'route_url' => NULL,
				'permission_id' => 22,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			26 => 
			array (
				'id' => 27,
				'route_name' => 'admin.metric-categories.create',
				'route_url' => NULL,
				'permission_id' => 23,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			27 => 
			array (
				'id' => 28,
				'route_name' => 'admin.metric-categories.edit',
				'route_url' => NULL,
				'permission_id' => 24,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			28 => 
			array (
				'id' => 29,
				'route_name' => 'metric-categories.destroy',
				'route_url' => NULL,
				'permission_id' => 25,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			29 => 
			array (
				'id' => 30,
				'route_name' => 'admin.metric-categories.show',
				'route_url' => NULL,
				'permission_id' => 43,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			30 => 
			array (
				'id' => 31,
				'route_name' => 'designation',
				'route_url' => NULL,
				'permission_id' => 26,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			31 => 
			array (
				'id' => 32,
				'route_name' => 'admin.designation.create',
				'route_url' => NULL,
				'permission_id' => 27,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			32 => 
			array (
				'id' => 33,
				'route_name' => 'admin.designation.edit',
				'route_url' => NULL,
				'permission_id' => 28,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			33 => 
			array (
				'id' => 34,
				'route_name' => 'designation.destroy',
				'route_url' => NULL,
				'permission_id' => 29,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			34 => 
			array (
				'id' => 35,
				'route_name' => 'admin.designation.show',
				'route_url' => NULL,
				'permission_id' => 44,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			35 => 
			array (
				'id' => 36,
				'route_name' => 'adminReport',
				'route_url' => NULL,
				'permission_id' => 34,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			36 => 
			array (
				'id' => 37,
				'route_name' => 'admin.revisions.show',
				'route_url' => NULL,
				'permission_id' => 37,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
		));
	}

}
