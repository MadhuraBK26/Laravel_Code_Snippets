<?php

use Illuminate\Database\Seeder;

class RegisterNavigators extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();
        $navigators = [
            ['name' => 'admin',
                'email' => 'admin@npat.com',
                'password' => bcrypt('admin123'),
                'user_id' => '1',
                'role_id' => '5',
                'is_super_admin' => '1',
            ],
        ];

        foreach ($navigators as $key => $dataValue) {
            DB::table('users')->insert([
                'name' => $dataValue['name'],
                'email' => $dataValue['email'],
                'password' => $dataValue['password'],
                'is_super_admin' => $dataValue['is_super_admin'],
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s"),
            ]);
//            DB::table('role_user')->insert([
//                'user_id' => $dataValue['user_id'],
//                'role_id' => $dataValue['role_id'],
//                'created_at' => date("Y-m-d h:i:s"),
//                'updated_at' => date("Y-m-d h:i:s"),
//            ]);
        }
    }

}
