<?php
/**
 * Created by PhpStorm.
 * User: DarkKris
 * Date: 2019/4/26
 * Time: 下午10:31
 */
namespace app\index\controller;

use app\index\model\CaseModel;
use app\index\validate\CaseValidate;
use think\Controller;

class CaseController extends Controller {

    private $is_login = false;
    private $is_admin = false;

    public function __construct() {
        parent::__construct();
        $login_controller = new Login();
        $result = $login_controller->checkLogin();
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

        $req = input('get.');

        if(!$this->is_login || !$this->is_admin) {
            return apiReturn(403,'用户权限不足或未登录',[]);
        }

        $result = $case_validate->scene('filterCount')->check($req);
        if ($result != true) {
            return apiReturn(500, $case_validate->getError(), []);
        }

        $condition = $this->getCondition($req);

        $result = $case_model->getData($condition);
        if ($result['code'] == CODE_SUCCESS) {
            return apiReturn(200, 'ok', count($result));
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

        $result = $case_model->getData($condition);
        // TODO 分配质检单 记得带上创建时间和创建人id
    }

    /**
     * 从请求中获取筛选条件
     * @param array $req
     * @return array
     */
    private function getCondition(array $req) {
        $condition = [];
        $condition['comment_result'] = $req['comment_result'];
        if($req['work_line']!=0)    $condition['work_line'] = $req['work_line'];
        if($req['be_test_team']!=0) $condition['be_test_team'] = $req['be_test_team'];
        if($req['problem_type']!=0) $condition['problem_type'] = $req['problem_type'];
        if($req['service_type']!=0) $condition['service_type'] = $req['service_type'];
        return $condition;
    }
}