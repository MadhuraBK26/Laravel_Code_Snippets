<?php

use Illuminate\Database\Seeder;

class PermissionGroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('permission_groups')->delete();

        \DB::table('permission_groups')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'group_name' => 'Projects',
                    'created_at' => '2015-10-21 00:00:00',
                    'updated_at' => '2015-10-21 00:00:00',
                    'position' => 1,
                    'slug' => 'projects',
                ),
            1 =>
                array(
                    'id' => 2,
                    'group_name' => 'Navigators',
                    'created_at' => '2015-10-21 00:00:00',
                    'updated_at' => '2015-10-21 00:00:00',
                    'position' => 2,
                    'slug' => 'navigators',
                ),
            2 =>
                array(
                    'id' => 3,
                    'group_name' => 'User Management',
                    'created_at' => '2015-10-21 00:00:00',
                    'updated_at' => '2015-10-21 00:00:00',
                    'position' => 3,
                    'slug' => 'users',
                ),
            3 =>
                array(
                    'id' => 4,
                    'group_name' => 'Metrics',
                    'created_at' => '2015-10-21 00:00:00',
                    'updated_at' => '2015-10-21 00:00:00',
                    'position' => 4,
                    'slug' => 'metrics',
                ),
            4 =>
                array(
                    'id' => 5,
                    'group_name' => 'Designations',
                    'created_at' => '2015-10-21 00:00:00',
                    'updated_at' => '2015-10-21 00:00:00',
                    'position' => 5,
                    'slug' => 'designations',
                ),
            5 =>
                array(
                    'id' => 6,
                    'group_name' => 'Roles',
                    'created_at' => '2015-10-21 00:00:00',
                    'updated_at' => '2015-10-21 00:00:00',
                    'position' => 6,
                    'slug' => 'roles',
                ),
            6 =>
                array(
                    'id' => 7,
                    'group_name' => 'Revisions',
                    'created_at' => '2015-10-21 00:00:00',
                    'updated_at' => '2015-10-21 00:00:00',
                    'position' => 7,
                    'slug' => 'revisions',
                ),
            7 =>
                array(
                    'id' => 8,
                    'group_name' => 'Reports',
                    'created_at' => '0000-00-00 00:00:00',
                    'updated_at' => '0000-00-00 00:00:00',
                    'position' => 8,
                    'slug' => 'reports',
                ),
            8 =>
                array(
                    'id' => 9,
                    'group_name' => 'Practices',
                    'created_at' => '0000-00-00 00:00:00',
                    'updated_at' => '0000-00-00 00:00:00',
                    'position' => 9,
                    'slug' => 'practices',
                ),
            9 =>
                array(
                    'id' => 10,
                    'group_name' => 'Feedback Form',
                    'created_at' => '0000-00-00 00:00:00',
                    'updated_at' => '0000-00-00 00:00:00',
                    'position' => 10,
                    'slug' => 'feedback-form',
                ),

        ));
    }

}
