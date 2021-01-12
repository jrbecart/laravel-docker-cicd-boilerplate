<?php

use Illuminate\Database\Seeder;

class AdminUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 
        $person = \App\Models\User::create([
          "name" => "jbecart@uottawa.ca",
          "email" => "jbecart@uottawa.ca",
          "password" => "mypassword1NotUsedAnyway",
        ]);
        $faculties = \App\Models\Faculty::all();
        $person->faculties()->sync($faculties,false);
        $laravelUser = \App\Models\User::where('email', '=', "jbecart@uottawa.ca")->first(); 
        $laravelUser->syncRoles(["admin"]);
        
        // Other admin
        // $person = ...
        
    }
}
 