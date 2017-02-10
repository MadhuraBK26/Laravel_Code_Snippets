<?php

use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder {

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('role_user')->delete();

        \DB::table('role_user')->insert(array (
            0 =>
                array (
                    'id' => '1',
                    'role_id' => '5',
                    'user_id' => '1',
                    'created_at' => '2015-10-01 00:00:00',
                    'updated_at' => '2015-10-01 00:00:00',
                ),
        ));
    }

}