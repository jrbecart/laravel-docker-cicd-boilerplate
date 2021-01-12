<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfUser
{
   
    /**
     * Handle an incoming request and check if an email is present in the session
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */ 
    public function handle($request, Closure $next)
    { 
        $data = \Session::all();
        
        if (!empty($data["email"]))
          return $next($request);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response(trans('backpack::base.unauthorized'), 401);
        } else {
            //return redirect('/'); 
            header("Location: " . docker_secret('PORTAL_APP_URL') . "/".  docker_secret('PORTAL_TEMPLATE_APP_DIR') . "/");
            exit();
        }

    }
}
 