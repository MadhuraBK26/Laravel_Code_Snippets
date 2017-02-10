<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(ExpectationTableSeeder::class);
        $this->call(InsertPracticesIntoProjectTable::class);
        $this->call(KRACategorySeeder::class);
        $this->call(NavigatorDesignationSeeder::class);
        $this->call(PeopleKRATableSeeder::class);
        $this->call(PermissionBasicTypesTableSeeder::class);
        $this->call(PermissionGroupsTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionRoutesTableSeeder::class);
        $this->call(Projects::class);
        $this->call(RegisterNavigators::class);
        $this->call(RolesTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(UserRolesSeeder::class);
        $this->call(DepartmentTableSeeder::class);

        
        
        
        
        



          

      
    
      

        
        Model::reguard();

	
	}
}
