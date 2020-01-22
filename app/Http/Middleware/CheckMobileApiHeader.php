<?php

namespace App\Http\Middleware;

use Closure;
use Response; 

class CheckMobileApiHeader
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
        if(!isset($_SERVER['HTTP_X_DEVICE_ID'])){  
            return Response::json(['status' => false , "message" =>  'Please set device id header'] , 400);  
        } 
        
        if(!isset($_SERVER['HTTP_X_DEVICE_TOKEN'])){  
            return Response::json(['status' => false , "message" => 'Please set device token header'] , 400);  
        } 

        if(!isset($_SERVER['HTTP_X_DEVICE_TYPE'])){  
            return Response::json(['status' => false , "message" => 'Please set device type header'] , 400);  
        } 

        return $next($request);  
    }
}