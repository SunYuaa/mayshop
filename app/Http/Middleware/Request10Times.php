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

        $key = $ip.'request10Times';
        $num = Redis::get($key);
        if($num>10){
            die('请求时间次数超过限制');
        }
        echo 'num:'.$num;echo '<br/>';
        Redis::incr($key);
        Redis::expire($key,10);

        echo date('Y-m-d H:i:s');echo '<hr/>';

        return $next($request);
    }
}
