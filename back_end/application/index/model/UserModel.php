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
                return $result;
            }else{
                return null;
            }
        }catch(Exception $e){
            return ['code'=>CODE_ERROR, 'message'=>'数据库错误', 'data'=>$e->getMessage()];
        }
    }
}