<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class CheckLogin
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
        //判断用户是否登录
        $token = $request->input('token');
        $id = $request->input('id');

        //判断非空
        if(empty($token) || empty($id)){
            $response = [
                'errcode' => 22011,
                'errmsg' => '参数不完整'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }

        //验证token是否有效
        $key = 'login_token:id:'.$id;
        $redis_token = Redis::get($key);
        if($redis_token){
            if($redis_token == $token){
                //记录日志
                $response = [
                    'errcode' => 0,
                    'errmsg' => 'ok'
                ];
            }else{
                $response = [
                    'errcode' => 22002,
                    'errmsg' => 'token无效'
                ];
            }
        }else{
            //token没有
            $response = [
                'errcode' => 20021,
                'errmsg' => 'token不存在,请先登录'
            ];
        }

        die(json_encode($response,JSON_UNESCAPED_UNICODE));

        return $next($request);
    }
}
