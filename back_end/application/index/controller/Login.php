<?php
/**
 * Created by PhpStorm.
 * User: DarkKris
 * Date: 2019/4/8
 * Time: 下午9:19
 */
namespace app\index\controller;

use app\index\model\UserModel;
use app\index\validate\UserValidate;
use think\Controller;

class Login extends Controller {

    public function checkLogin() {
        if(\Session::has('user_id')) {
            $user_model = new UserModel();
            $result = $user_model->isAdmin(session('user_id'));
            if($result['code']==CODE_SUCCESS){
                if($result['data']){
                    return apiReturn(200,'ok','admin');
                }else{
                    return apiReturn(200,'ok','not-admin');
                }
            }else{
                return apiReturn(500,$result['message'], $result['data']);
            }
        }
        return apiReturn(200,'ok',false);
    }

    public function login() {
        $user_validate = new UserValidate();
        $user_model = new UserModel();

        $req = input('post.');

        $result = $user_validate->scene('login')->check($req);
        if($result!=true)
            return apiReturn(500,$user_validate->getError(),[]);

        $result = $user_model->checkUser($req['username'],$req['password']);
        if($result['code']==CODE_SUCCESS) {
            session('user_id',$result['data']['user_id']);
            session('username',$result['data']['username']);
            return apiReturn(200,'登录成功',null);
        }else{
            return apiReturn(403,'账号或密码输入错误，请重新输入。',null);
        }
    }

    public function logout() {
        session(null);
        return apiReturn(200,'ok',null);
    }

}