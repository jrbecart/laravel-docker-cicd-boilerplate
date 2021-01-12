<?php

namespace App\Http\Middleware;

use Closure;

class LogRouteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if ($request->ip() !== '127.0.0.1')
      {
        if (backpack_auth()->user())
          \Log::info("[ACCESS] Admin " . backpack_auth()->user()->email . ": " . $request->fullUrl() . ' ' . $this->getIp($request));
        elseif (\Auth::user())
          \Log::info("[ACCESS] User " . \Auth::user()->email . ": " . $request->fullUrl() . ' ' . $this->getIp($request));
        else
          \Log::info("[ACCESS] Guest: " . $request->fullUrl() . ' ' . $this->getIp($request));
      }
      
      return $next($request);
    } 
    
    /**
     * Return real IP client not the proxy or load balancer ones
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function getIp($request){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); 
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return $request->ip();
    }
}
 