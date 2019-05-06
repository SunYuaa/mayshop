<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\WxUsersModel;
use App\Model\ApUsersModel;

class apiController extends Controller
{
    //get 方式
    public function getM()
    {
        $id = request()->input();
        $userInfo = WxUsersModel::where(['id'=>$id])->first();
        $data = [];
        if($userInfo){
//            var_dump($userInfo->toArray());
            $data = [
                'errcode' => 0,
                'errmsg' => 'success'
            ];
        }else{
            $data = [
                'errcode' => 22001,
                'errmsg' => 'fail'
            ];
        }
        return json_encode($data);

    }

    //post 方式
    public function postM()
    {
        $request = request()->input();
        $info = [
            'name' => $request['name'],
            'email' => $request['email'],
        ];
        $id = ApUsersModel::insert($info);
        $data = [];
        if($id){
            $data = [
                'errcode' => 0,
                'errmsg' => 'success'
            ];
        }else{
            $data = [
                'errcode' => 22001,
                'errmsg' => 'fail'
            ];
        }
        return json_encode($data);
    }

    //
    public function test()
    {
        echo __METHOD__;echo '<br/>';
        var_dump($_POST);echo '<br/>';

        $str = file_get_contents('php://input');
        echo $str;
    }

    //全局中间件
    public function reqMid()
    {
        echo __METHOD__;
    }
}
