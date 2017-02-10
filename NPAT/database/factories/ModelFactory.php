<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
 */

$factory->define(App\Models\User::class, function ($faker) {
    return [
        'name'           => $faker->name,
        'email'          => $faker->email,
        'password'       => bcrypt('compass'),
        'remember_token' => bcrypt('compass'),
        'navigator_designation_id'=>$faker->numberBetween($min = 1, $max = 50),
        'reporting_manager_id'=>$faker->numberBetween($min = 1, $max = 51),
        'emp_id'=>$faker->numberBetween($min = 1, $max = 600),
        'practices_id'=>$faker->numberBetween($min = 1, $max = 6),
        'status'=>'1'
    ];
});

$factory->define(App\Models\RoleUser::class,function($faker){
   return[

 'role_id'=>$faker->numberBetween($min = 1, $max = 5),

   ];
});

$factory->define(App\Models\Project::class,function($faker){
    return[
        'name'=>$faker->company,
        'status'=>'1',
        'project_created_date'=>$faker->date($format = 'Y-m-d', $max = 'now'),
        'project_end_date'=>$faker->date($format = 'Y-m-d', $max = 'now'),

    ];
});

$factory->define(App\Models\ProjectManager::class,function($faker){
    return[
        'project_id' =>$faker->numberBetween($min = 1, $max = 50),
        'manager_id' =>$faker->numberBetween($min = 1, $max = 50),
        'status'=>'1',
        'start_date'=>$faker->date($format = 'Y-m-d', $max = 'now'),
        'end_date'=>$faker->date($format = 'Y-m-d', $max = 'now'),
        'percentage_involved'=> '100%',

    ];
});

$factory->define(App\Models\PeopleFeedback::class,function($faker){
    return[
        'project_id' =>$faker->numberBetween($min = 1, $max = 25),
        'manager_id' =>$faker->numberBetween($min = 1, $max = 25),
        'status'=>'1',

        'start_date'=>$faker->date($format = 'Y-m-d', $max = 'now'),
        'end_date'=>$faker->date($format = 'Y-m-d', $max = 'now'),
    ];
});