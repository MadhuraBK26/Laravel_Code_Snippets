// app/database/seeds/UserTableSeeder.php
<?php

class UserTableSeeder extends Seeder
{
  public function run()
  {
    DB::table('users')->delete();
    
    User::create(array(
    	'name'     => 'Rakesh',
        'email'    => 'sharmarakesh395@gmail.com',
        'password' => Hash::make('mypass'),
    ));
  }
}