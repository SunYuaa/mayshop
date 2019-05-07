<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class Request10Times
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
        $ip =  $_SERVER['SERVER_ADDR'];  //192.168.140.129
        $key = 'request10Times:ip:'.$ip.':token:'.$request->input('token');
        $num = Redis::get($key);
        if($num>10){
            die('请求时间次数超过限制');
        }
        echo '<hr/>';
        echo 'num:'.$num;
        echo 'key:'.$key;
        echo '<hr/>';
        
        Redis::incr($key);
        Redis::expire($key,10);

        return $next($request);
    }
}
