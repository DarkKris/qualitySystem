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

    public function getDataWithCondition(array $condition,array $field = [], $distinct = false) {
        try {
            $data = $this->where($condition);

            if($field!=[])
                $data = $data->field($field);
            if($distinct)
                $data = $data->distinct($distinct);

            $data = $data->select();
            return ['code'=>CODE_SUCCESS, 'message'=>'OK', 'data'=>$data ];
        } catch(Exception $e) {
            return ['code'=>CODE_ERROR, 'message'=>'数据库错误', 'data'=>$e->getMessage()];
        }
    }

    public function getDataJoinUser(array $condition) {
        try {
            $data = $this;
            // 处理qa_id查询
            if(isset($condition['qa_id']))
            {
                if($condition['qa_id']=='not null')
                {
                    $data = $data->where('qa_id','not null');
                    unset($condition['qa_id']);
                }else if($condition['qa_id']=='null') {
                    $data = $data->where('qa_id','null');
                    unset($condition['qa_id']);
                }else{
                    // qa_id 单个查询
                    // pass
                }
            }
            // 处理created_time查询
            if(isset($condition['created_time']))
            {
                $condition['created_time'][0] = substr($condition['created_time'][0],0,10);
                $condition['created_time'][1] = substr($condition['created_time'][1],0,10);
                $data->whereTime('created_time','between',[$condition['created_time'][0], $condition['created_time'][1]]);
                unset($condition['created_time']);
            }
            // join用户表
            $data = $data->where($condition)
                ->alias('c')
                ->leftJoin('user u1','u1.user_id = c.created_user')
                ->leftJoin('user u2', 'u2.user_id = c.worker_id')
                ->field([
                    'case_id',
                    'work_line',
                    'be_test_team',
                    'be_test_servicer',
                    'service_type',
                    'problem_type',
                    'comment_result',
                    'qa_id',
                    'u1.user_id' => 'creater_id',
                    'u1.usernick' => 'creater_name',
                    'created_time',
                    'u2.user_id' => 'worker_id',
                    'u2.usernick' => 'worker_name',
                    'grade',
                    'status'
                ])
                ->select();

            return ['code'=>CODE_SUCCESS, 'message'=>'OK', 'data'=>$data->toArray()];
        }catch(Exception $e) {
            return ['code'=>CODE_ERROR, 'message'=>'数据库错误', 'data'=>$e->getMessage()];
        }
    }

    public function checkPrivi($worker_id, $qa_id) {
        try {
            $result = $this->where([
                'worker_id' => $worker_id,
                'qa_id' => $qa_id
            ])->find();

            if($result) {
                return ['code'=>CODE_SUCCESS, 'message'=>'ok', 'data'=>true];
            }else{
                return ['code'=>CODE_SUCCESS, 'message'=>'ok', 'data'=>false];
            }
        } catch(Exception $e) {
            return ['code'=>CODE_ERROR, 'message'=>'数据库错误', 'data'=>$e->getMessage()];
        }
    }

    public function setGrade($qa_id, array $grade) {
        try {
            $data = $this->where('qa_id',$qa_id)->field('grade')->find();
            $data = $data['grade'];

            if($data!='' && $data!=NULL && $data!=[]) {
                return ['code'=>CODE_ERROR, 'message'=>'禁止重复打分', 'data'=>[]];
            }

            $result = $this->where('qa_id',$qa_id)
                ->setField('grade',json_encode($grade));

            if($result) {
                $this->where('qa_id',$qa_id)->setField('status',2);
                return ['code'=>CODE_SUCCESS, 'message'=>'ok', 'data'=>true];
            }else{
                return ['code'=>CODE_ERROR, 'message'=>'打分失败', 'data'=>[]];
            }
        } catch(Exception $e) {
            return ['code'=>CODE_ERROR, 'message'=>'数据库错误', 'data'=>$e->getMessage()];
        }
    }

    public function setHandout($case_id, $creater, $worker, $time) {
        try {
            $result = $this->where('case_id',$case_id)
                    ->update([
                        'qa_id'=>$case_id,
                        'worker_id'=>$worker,
                        'status'=>1,
                        'created_time'=>date('Y-m-d H:i:s',$time),
                        'created_user'=>$creater
                    ]);
            if($result) {
                return ['code'=>CODE_SUCCESS, 'message'=>'ok', 'data'=>true];
            }else{
                return ['code'=>CODE_ERROR, 'message'=>'分配失败', 'data'=>false];
            }
        } catch (Exception $e) {
            return ['code'=>CODE_ERROR, 'message'=>'数据库错误', 'data'=>$e->getMessage()];
        }
    }
}