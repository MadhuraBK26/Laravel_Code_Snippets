<?php

use Illuminate\Database\Seeder;

class PracticesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('practices')->delete();

        $practicesData = ["DOT NET Practices",
            "JAVA Practices",
            "PHP Practices",
            "UI Practices",
            "Testing Practices",
            "BA Practices"
        ];
        foreach ($practicesData as $practices) {
            DB::table('practices')->insert([
                'practices' => $practices,
                'status' => '1',
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s")
            ]);
        }
    }
}
