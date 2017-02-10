<?php

use Illuminate\Database\Seeder;

class ExpectationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('expectation')->delete();
        $data = [
	            "Did not meet Expectations",
	            "Partially Achieved Expectations",
	            "Achieved Expectations",
	            "Exceeded Expectations",
	            "Far Exceeded Exceptions"
             ];
        $i=1;
         foreach ($data as $key => $dataValue) {
            	DB::table('expectation')->insert([
                'name' => $dataValue,
                'status' => '1',
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s"),
                 'expectation_value' => $i
            ]);
            $i++;
    	}
    }
}
