<?php
/**
 * Created by PhpStorm.
 * User: DarkKris
 * Date: 2019/4/8
 * Time: 下午9:34
 */
namespace app\index\model;

use think\Exception;
use think\Model;

class UserModel extends Model {
    protected $table = 'user';

    public function checkUser($username,$password) {
        $hash_password = md5(base64_encode($password));

        try {
            $result = $this->where([
                'username' => $username,
                'password' => $hash_password
            ])->find();
            if($result) {
                return ['code'=>CODE_SUCCESS, 'message'=>'OK', 'data'=>$result];
            }else{
                return ['code'=>CODE_SUCCESS, 'message'=>'OK', 'data'=>null];
            }
        }catch(Exception $e){
            return ['code'=>CODE_ERROR, 'message'=>'数据库错误', 'data'=>$e->getMessage()];
        }
    }

    public function isAdmin($user_id) {
        try {
            $info = $this->where('user_id')->find();
            if ($info['admin'] == true) return ['code'=>CODE_SUCCESS, 'message'=>'OK', 'data'=>true];
            else return ['code'=>CODE_SUCCESS, 'message'=>'OK', 'data'=>false];
        }catch(Exception $e) {
            return ['code'=>CODE_ERROR, 'message'=>'数据库错误', 'data'=>$e->getMessage()];
        }
    }
}