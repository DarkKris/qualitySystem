<?php
/**
 * Created by PhpStorm.
 * User: DarkKris
 * Date: 2019/4/26
 * Time: 下午10:34
 */
namespace app\index\model;

use think\Model;
use think\Exception;

class CaseModel extends Model {

    protected $table = 'case';

    public function getData(array $condition) {
        try {
            $this->where($condition)->select();
        } catch(Exception $e) {
            return ['code'=>CODE_ERROR, 'message'=>'数据库错误', 'data'=>$e->getMessage()];
        }
    }
}