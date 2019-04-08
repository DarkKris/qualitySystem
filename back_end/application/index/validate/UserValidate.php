<?php
/**
 * Created by PhpStorm.
 * User: DarkKris
 * Date: 2019/4/8
 * Time: 下午9:20
 */
namespace app\index\validate;

use think\Validate;

class UserValidate extends Validate {
    protected $rule = [
        'username' => 'require|length:8',
        'password'=>'require',
    ];

    protected $message = [
        'username.require'=>'账号必须',
        'username.length'=>'账号长度必须是8位',
        'password.require'=>'密码必须'
    ];

    protected $scene = [
        'login'=>['username','password'],
    ];
}