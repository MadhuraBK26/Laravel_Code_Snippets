<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('department')->delete();
        $departmentData = ["Delivery",
            "Operaions",
            "Active AI",
            "GRC",
            "IES",
            "Marinotech",
            "Solution Architect"
        ];

        $currentDate = Carbon::now();

        foreach ($departmentData as $department) {
            DB::table('department')->insert([
                'department_name' => $department,
                'status' => '1',
                'created_at' => $currentDate,
                'updated_at' => $currentDate
            ]);
        }
    }
}
