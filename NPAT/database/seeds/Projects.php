<?php

use Illuminate\Database\Seeder;

class Projects extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('project')->delete();

        $projects = ['NPAT', 'MyCloud Bin', 'Rebel Networker', 'Groovon', 'Blue Stone'];

        foreach ($projects as $key => $projectName) {
            DB::table('project')->insert([
                'name' => $projectName,
                'status' => '1',
                'project_created_date' => date("Y-m-d h:i:s"),
                'project_end_date' => date("Y-m-d h:i:s"),
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s"),
            ]);
        }
    }
}