<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class userController extends Controller
{
    //注册
    public function register()
    {
        $password = request()->input('password');
        $repwd = request()->input('repwd');
        $email = request()->input('email');

        //验证确认密码和密码是否一致
        if($password != $repwd){
            $response = [
                'errcode' => '22001',
                'errmsg' => '确认密码和密码不一致'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        //验证邮箱唯一
        $e = UserModel::where(['email'=>$email])->first();
        if($e){
            $response = [
                'errcode' => '22002',
                'errmsg' => '邮箱已存在'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }

        //入库
        //密码加密
        $pass = password_hash($password,PASSWORD_BCRYPT);
        $info = [
            'name' => request()->input('name'),
            'password' => $pass,
            'email' => $email,
            'create_time' => time()
        ];
        $id = UserModel::insertGetId($info);
        if($id){
            $response = [
                'errcode' => 0,
                'errmsg' => '注册成功'
            ];
        }else{
            $response = [
                'errcode' => 22010,
                'errmsg' => '注册成功'
            ];
        }
        die(json_encode($response,JSON_UNESCAPED_UNICODE));
    }

    //登录
    public function login(Request $request)
    {
        $email = $request->input('email');
        $pass = $request->input('password');

        $user = UserModel::where(['email'=>$email])->first();
        if($user){
            //用户存在   验证密码
            if (password_verify($pass,$user->password)){
                //获取token redis存储
                $token = $this->getLoginToken($user->id);
                $login_token_key = 'login_token:id:'.$user->id;
                Redis::set($login_token_key,$token);
                Redis::expire($login_token_key,7*24*3600);

                //登录成功
                $response = [
                    'errcode' => 0,
                    'errmsg' => 'SUCCESS'
                ];
            }else{
                //密码不正确
                $response = [
                    'errcode' => 22010,
                    'errmsg' => '密码错误'
                ];
            }
        }else{
            //用户不存在
            $response = [
                'errcode' => 22021,
                'errmsg' => '用户不存在'
            ];
        }
        die(json_encode($response,JSON_UNESCAPED_UNICODE));
    }

    //个人中心
    public function userCenter()
    {
        echo __METHOD__;
    }

    /**
     * 获取loginToken
     * @param $id
     * @return bool|string
     */
    public function getLoginToken($id){
        return substr(sha1($id.time().Str::random(15)),5,20);
    }
}
