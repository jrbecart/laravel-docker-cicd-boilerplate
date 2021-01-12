<?php

use Illuminate\Database\Seeder;

class FacultyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('faculties')->insert([
            ['name' => 'Science', 'name_fr' => 'Sciences', 'long_name' => 'Faculty of Science', 'long_name_fr' => 'Faculté des sciences',],
            ['name' => 'Engineering', 'name_fr' => 'Génie', 'long_name' => 'Faculty of Engineering', 'long_name_fr' => 'Faculté de Génie',],
            ['name' => 'Science and Engineering', 'name_fr' => 'Sciences et Génie', 'long_name' => 'Faculty of Science and Engineering', 'long_name_fr' => 'Faculté des sciences et de génie',],
        ]);
        
        
        
    }
}
