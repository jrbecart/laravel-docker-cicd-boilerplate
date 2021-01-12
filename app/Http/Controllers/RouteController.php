<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SessionReaderController;


class RouteController extends Controller
{

    /**
     * Home route
     */
    public function home()
    {
        $sessionHelper = new SessionReaderController();
        $userInfos = $sessionHelper->getUser();

        if(!empty($userInfos))
        {
            $email = $userInfos["email"];
            if(!empty($email))
            {
                \session(['email' => $email]);
                $adminUser = \App\Models\User::where('email', '=', $email)->first();
                if($adminUser)
                {
                    Auth::guard('backpack')->login($adminUser);
                    //Auth::login($adminUser);
                    //Auth::guard('web')->login($adminUser);
                    //dd(backpack_user());
                    if (backpack_user()) {
                        return redirect('admin');
                    }
                }
                
                // return redirect()->route('enduser-route...');
                 return view('home', compact('email')); // view('welcome');
            }
        }
        
        //redirect to login portal
        header("Location: " . docker_secret('PORTAL_APP_URL') ."/applogin/template");
        exit();
    }
    
    /**
     * healthcheck route
     */
    public function healthcheck()
    {
        return view('healthcheck');
    }

    /**
     * Logout route
     */
    public function logout()
    {

        if (backpack_user()) {
            Auth::guard('backpack')->logout(); // do auth logout for backpack
        }
        Auth::logout();
        \Session::flush();

        // delete the cookie session
        $app_name = \Str::slug(trim(docker_secret('PORTAL_APP_NAME')), '_');

        // delete session information in REDIS
        $sessionHelper = new SessionReaderController();
        $userInfos = $sessionHelper->removeUser();

        \Cookie::queue(\Cookie::forget($app_name . "_session"));
        setcookie($app_name . "_session", "", time() - 3600, "/");
        setcookie($app_name . "_session", "", time() - 3600);

        // Redirect to adfs logout
        $logout = docker_secret('PORTAL_ADFS_LOGOUT');
        header("Refresh:1; url=$logout");

        exit();
    }

}
