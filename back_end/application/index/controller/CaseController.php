<?php
/**
 * Created by PhpStorm.
 * User: DarkKris
 * Date: 2019/4/26
 * Time: 下午10:31
 */
namespace app\index\controller;

use app\index\model\CaseModel;
use app\index\model\UserModel;
use app\index\validate\CaseValidate;
use think\Controller;

class CaseController extends Controller {

    private $is_login = false;
    private $is_admin = false;

    public function __construct() {
        parent::__construct();
        $login_controller = new Login();
        $result = $login_controller->checkLogin();
        $result = json_decode($result,true);
        if($result['code']==200) {
            if($result['data']!=false) {
                $this->is_login = true;
                // 是否是程序员
                $this->is_admin = $result['data']['admin']=='1'?true:false;
            }
        }
    }

    /**
     * 获取筛选总数
     * @return \think\response\Json
     */
    public function filterCount()
    {
        $case_validate = new CaseValidate();
        $case_model = new CaseModel();

        $req = input('post.');

        if(!$this->is_login || !$this->is_admin) {
            return apiReturn(403,'用户权限不足或未登录',[]);
        }

        $result = $case_validate->scene('filterCount')->check($req);
        if ($result != true) {
            return apiReturn(500, $case_validate->getError(), []);
        }

        $condition = $this->getCondition($req);

//        halt($req);
//        halt($condition);

        $result = $case_model->getDataWithCondition($condition);
//        halt($result);
        if ($result['code'] == CODE_SUCCESS) {
            return apiReturn(200, 'ok', count($result['data']));
        }else {
            return apiReturn(500,$result['message'],$result['data']);
        }
    }

    /**
     * 分配质检单
     * @return \think\response\Json
     */
    public function filterHandout() {
        $case_validate = new CaseValidate();
        $case_model = new CaseModel();

        $req = input('post.');

        if(!$this->is_login || !$this->is_admin) {
            return apiReturn(403,'用户权限不足或未登录',[]);
        }

        $result = $case_validate->scene('filterHandout')->check($req);
        if($result!=true) {
            return apiReturn(500,$case_validate->getError(),[]);
        }

        $condition = $this->getCondition($req);

        $result = $case_model->getDataWithCondition($condition);
        // TODO 分配质检单 记得带上创建时间和创建人id
    }

    /**
     * 获取被质检单位列表
     * @return string
     */
    public function getBeTestTeam() {
        $case_model = new CaseModel();

        if(!$this->is_login) {
            return apiReturn(403,'用户权限不足或未登录',[]);
        }

        $result = $case_model->getDataWithCondition([],['be_test_team'],true);

        if($result['code']==CODE_SUCCESS) {
            return apiReturn(200, 'ok', $result['data']);
        }else{
            return apiReturn(500,$result['message'],$result['data']);
        }
    }

    /**
     * 获取质检员列表
     * @return string
     */
    public function getTestWorker() {
        $user_model = new UserModel();

        if(!$this->is_login) {
            return apiReturn(403,'用户权限不足或未登录',[]);
        }

        $result = $user_model->getDataWithCondition(['admin'=>'0'],['user_id','usernick']);

        if($result['code']==CODE_SUCCESS) {
            return apiReturn(200, 'ok', $result['data']);
        }else{
            return apiReturn(500,$result['message'],$result['data']);
        }
    }

    /**
     * 获取创建人列表
     * @return string
     */
    public function getCreater() {
        $user_model = new UserModel();

        if(!$this->is_login) {
            return apiReturn(403,'用户权限不足或未登录',[]);
        }

        $result = $user_model->getDataWithCondition(['admin'=>'1'],['user_id','usernick']);

        if($result['code']==CODE_SUCCESS) {
            return apiReturn(200, 'ok', $result['data']);
        }else{
            return apiReturn(500,$result['message'],$result['data']);
        }
    }

    /**
     * 获取质检单列表
     * TODO  unhandle out 未写完的
     * admin/worker权限鉴定
     * @return string
     */
    public function getCaseData() {
        $case_model = new CaseModel();
        $case_validate = new CaseValidate();

        $req = input('post.');
        $result = null;

        if($this->is_login && $this->is_admin) {
            $result = $case_validate->scene('getCaseData.admin')->check($req);
        } else if($this->is_login) {
                $result = $case_validate->scene('getCaseData.worker')->check($req);
        } else {
            return apiReturn(403,'用户权限不足或未登录',[]);
        }

        if ($result != true) {
            return apiReturn(500, $case_validate->getError(), []);
        }

        $condition = []; // todo handle condition

        $result = $case_model->getDataWithCondition($condition,[],false);

        if($result['code']==CODE_SUCCESS) {
            return apiReturn(200, 'ok', $result['data']);
        }else{
            return apiReturn(500,$result['message'],$result['data']);
        }
    }

    /**
     * 从请求中获取筛选条件
     * @param array $req
     * @return array
     */
    private function getCondition(array $req) {
        $condition = [];
        $condition['comment_result'] = array_values($req['comment_result']);
        if($req['work_line']!=0)    $condition['work_line'] = $req['work_line'];
        if($req['be_test_team']!=0) $condition['be_test_team'] = $req['be_test_team'];
        if($req['problem_type']!=0) $condition['problem_type'] = $req['problem_type'];
        if($req['service_type']!=0) $condition['service_type'] = $req['service_type'];
        return $condition;
    }
}