<?php

use Illuminate\Database\Seeder;

class NavigatorDesignationSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('navigator_designations')->delete();
        $rolesData = ["Delivery Head",
            "Lead Consultant",
            "Senior Consultant",
            "Consultant",
            "Intern/Associate Consultant",
            "Admin"
        ];
        foreach ($rolesData as $name) {
            DB::table('navigator_designations')->insert([
                'name' => $name,
                'status' => '1',
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s")
            ]);
        }
    }
}