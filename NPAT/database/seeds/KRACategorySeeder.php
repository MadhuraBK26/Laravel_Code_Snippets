<?php

use Illuminate\Database\Seeder;

class KRACategorySeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kra_category')->delete();

        $rolesData = [
            ["name" => "Delivery Focus", "sort" => "1", "color" => "Green"],
            ["name" => "Bussiness Focus", "sort" => "2", "color" => "Blue"],
            ["name" => "People Focus", "sort" => "3", "color" => "Orange"],
        ];
        foreach ($rolesData as $row) {
            DB::table('kra_category')->insert([
                'name' => $row['name'],
                'sort' => $row['sort'],
                'color' => $row['color'],
                'status' => '1',
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s"),
            ]);
        }
    }
}