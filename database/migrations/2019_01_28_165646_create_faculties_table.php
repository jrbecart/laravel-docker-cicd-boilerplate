<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacultiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('name'); //Ie Science
            $table->string('name_fr'); //Ie Sciences
            $table->string('long_name'); //Ie Faculty of Science
            $table->string('long_name_fr'); //Ie Faculté des sciences
            $table->timestamps();
        });
        
    } 
 
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faculties');
    }
}
