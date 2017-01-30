<?php

use Illuminate\Database\Seeder;
use App\pTicketCalculation;

class UsersTablSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pTicketCalculation')->delete();
    
        pTicketCalculation::create(array(
    	'name'     => 'Rakesh',
       
         'carNumber'=>'KA 12',
         'carModel'=>'Toyota',
          'farePerDay'=>'1',
          'noOfDays'=>'1',
          'noOfCars'=>'2'
        'email'    => 'sharmarakesh395@gmail.com',
        'password' => Hash::make('mypass'),
    }
}
