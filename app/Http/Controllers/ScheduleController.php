<?php

namespace App\Http\Controllers;


use App\Traits\ApiTrait;
use Illuminate\Queue\SerializesModels;

class ScheduleController extends Controller
{
    use ApiTrait, SerializesModels;
  
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    
    public function example()
    {
        \Log::stack(['teams'])->info(date("Y-m-d"));
    }
}
 