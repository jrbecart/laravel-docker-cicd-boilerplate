<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        
        $controllerList = ["Faculty","User", "Role", "Permission"];
        foreach($controllerList as $controllerName)
        {
            DB::table('permissions')->insert([
                ['name' => 'list ' . $controllerName , 'guard_name' => 'backpack',],
                ['name' => 'delete ' . $controllerName , 'guard_name' => 'backpack',],
                ['name' => 'create ' . $controllerName , 'guard_name' => 'backpack',],
                ['name' => 'update ' . $controllerName , 'guard_name' => 'backpack',],
                ['name' => 'show ' . $controllerName , 'guard_name' => 'backpack',],
            ]);
        }   
    }
}
