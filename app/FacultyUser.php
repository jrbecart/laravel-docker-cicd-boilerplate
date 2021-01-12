<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FacultyUser extends Pivot
{
    //
    protected $table = 'faculty_user';
}
