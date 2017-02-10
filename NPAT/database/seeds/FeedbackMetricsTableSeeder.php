<?php

use Illuminate\Database\Seeder;

class FeedbackMetricsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('feedback_metrics')->delete();
        $data = [
                    "Project Role(s) and Responsibilities",
                    "Performance Summary",
                    "Accountability & Initiative",
                    "Problem Solving and Technical Skills",
                    "Quality",
                    "Timeliness",
                    "Communication",
                    "Collaboration(with Team and Stakeholder)",
                    "Suggestions for Development"
                 ];
         foreach ($data as $key => $dataValue) {
                	DB::table('feedback_metrics')->insert([
                    'metrics' => $dataValue,
                    'status' => '1',
                    'created_at' => date("Y-m-d h:i:s"),
                    'updated_at' => date("Y-m-d h:i:s")
                    ]);
    	        }
	}	
}
