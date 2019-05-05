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
        if($userInfo){
            var_dump($userInfo->toArray());
        }else{
            echo '查无此人';
        }

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
}
