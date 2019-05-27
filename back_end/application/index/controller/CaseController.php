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
use think\exception\ErrorException;
use think\facade\Env;
use think\Response;

require_once Env::get('root_path') . "extend/PHPExcel.php";

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
                // 是否是管理员
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
        $condition['status']=0;

        $result = $case_model->getDataWithCondition($condition);
        if ($result['code'] == CODE_SUCCESS) {
            return apiReturn(200, 'ok', count($result['data']));
        }else {
            return apiReturn(500,$result['message'],$result['data']);
        }
    }

    /**
     * 分配质检单
     * @return string
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
        $condition['status']=0; // 未分配的质检单
        $handout_total = $req['handout_total'];

        $result = $case_model->getDataWithCondition($condition);
        if($result['code']==CODE_SUCCESS) {
            // 分配质检单 带上创建时间和创建人id
            $data = $result['data']->toArray();
            $size = count($data);
            $now = time();
            if($req['handout_type']==0) {
                // 平均分配
                $limit = $size / count($req['handout_worker']);
                $total = 0;
                $count = 0;
                $user_count = 0;
                foreach ($data as $key => $value) {
                    $result = $case_model->setHandout($value['case_id'],session('user_id'),$req['handout_worker'][$user_count],$now);
                    if($result['code']==CODE_ERROR) {
                        return apiReturn(500,$result['message'],[]);
                    }
                    $count++;
                    $total++;
                    if($count==$limit) {
                        $count=0;
                        $user_count++;
                    }
                    if($user_count > count($req['handout_worker'])) {
                        $user_count--;
                    }
                    if($total == $handout_total) {
                        break;
                    }
                }
            }else{
                // 手动分配
                $handout_data = $req['handout_data'];
                // 处理删除空项
                foreach ($handout_data as $key => $value) {
                    if($value['worker_id']==0) {
                        unset($handout_data[$key]);
                    }
                }
                $count = 0;
                $user_count = 0;
                $total = 0;
                foreach ($data as $key => $value) {
                    $result = $case_model->setHandout($value['case_id'],session('user_id'),$handout_data[$user_count]['worker_id'],$now);
                    if($result['code']==CODE_ERROR) {
                        return apiReturn(500,$result['message'],[]);
                    }
                    $count++;
                    $total++;
                    if($count==$handout_data[$user_count]['caseNum']) {
                        $user_count ++;
                        $count = 0;
                    }
                    if($total == $handout_total) {
                        break;
                    }
                }
            }
        }else{
            return apiReturn(500,$result['message'],[]);
        }
        return apiReturn(200,'ok',null);
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
     * admin/worker权限鉴定
     * @return string
     */
    public function getCaseData() {
        $case_model = new CaseModel();

        $req = input('post.');
        $result = null;

        if(!$this->is_login) {
            return apiReturn(403,'用户权限不足或未登录',[]);
        }

        try {
            if(!isset($req['condition'])) {
                return apiReturn(500,'缺少字段', []);
            }

            $condition = $req['condition'];

            if(isset($condition['worker_id'])) {
                if($condition['worker_id']==0) {
                    $condition['worker_id'] = session('user_id');
                }
            }

            $result = $case_model->getDataJoinUser($condition);

            if ($result['code'] == CODE_SUCCESS) {
                return apiReturn(200, 'ok', $result['data']);
            } else {
                return apiReturn(500, $result['message'], $result['data']);
            }
        } catch (ErrorException $errorException) {
            return apiReturn(500,'系统错误', $errorException->getMessage());
        }
    }

    /**
     * 检查某质检员是否有某质检单的评价/查看权限
     * @return string
     */
    public function checkPrivilege()
    {
        $case_model = new CaseModel();
        $case_validate = new CaseValidate();

        $req = input('post.');

        if(!$this->is_login) {
            return apiReturn(403,'用户未登录',[]);
        }

        $result = $case_validate->scene('checkPrivilege')->check($req);
        if($result != true) {
            return apiReturn(500, $case_validate->getError(), []);
        }

        if($req['worker_id']==0) {
            $req['worker_id'] = session('user_id');
        }

        $result = $case_model->checkPrivi($req['worker_id'],$req['qa_id']);

        if($result['code']==CODE_SUCCESS) {
            return apiReturn(200,'ok',$result['data']);
        }else{
            return apiReturn(500,$result['message'], $result['data']);
        }
    }

    /**
     * 给质检单打分
     * @return string
     */
    public function markCase()
    {
        $case_model = new CaseModel();
        $case_validate = new CaseValidate();

        $req = input('post.');

        if(!$this->is_login) {
            return apiReturn(403,'用户未登录',[]);
        }

        $result = $case_validate->scene('markCase')->check($req);
        if($result != true) {
            return apiReturn(500, $case_validate->getError(), []);
        }

        $result = $case_model->checkPrivi(session('user_id'),$req['qa_id']);
        if($result['code']==CODE_SUCCESS) {
            if(!$result['data']) {
                return apiReturn(403,'用户没有权限',[]);
            }
        }else{
            return apiReturn(500,$result['message'], $result['data']);
        }

        $grade = [
            'ceremony'      => $req['ceremony'],
            'sysopt'        => $req['sysopt'],
            'messagetrans'  => $req['messagetrans'],
            'pinpoint'      => $req['pinpoint'],
            'quickhandle'   => $req['quickhandle']
        ];

        $result = $case_model->setGrade($req['qa_id'],$grade);

        if($result['code']==CODE_SUCCESS) {
            return apiReturn(200,'ok',[]);
        }else{
            return apiReturn(500,$result['message'], $result['data']);
        }
    }

    /**
     * 获取Case客户服务记录
     * @return string
     */
    public function getCaseMsg() {
        $case_model = new CaseModel();
        $case_validate = new CaseValidate();

        $req = input('post.');

        if(!$this->is_login) {
            return apiReturn(403,'用户未登录',[]);
        }

        $result = $case_validate->scene('getCaseMsg')->check($req);
        if($result != true) {
            return apiReturn(500, $case_validate->getError(), []);
        }

        if(!$this->is_admin) {
            // 检验权限
            $result = $case_model->checkPrivi(session('user_id'),$req['qa_id']);
            if($result['code']==CODE_SUCCESS) {
                if(!$result['data']) {
                    return apiReturn(403,'用户没有权限',[]);
                }
            }else{
                return apiReturn(500,$result['message'], $result['data']);
            }
        }

        $result = $case_model->getDataWithCondition(['qa_id'=>$req['qa_id']],['message']);
        if($result['code']==CODE_SUCCESS) {
            return apiReturn(200,'ok',$result['data'][0]['message']);
        }else{
            return apiReturn(500,$result['message'], $result['data']);
        }
    }

    public function exportExcel() {
        $case_model = new CaseModel();

        $req = input('post.');
        $result = null;

        if(!$this->is_login) {
            return apiReturn(403,'用户权限不足或未登录',[]);
        }

        try {
            if(!isset($req['condition'])) {
                return apiReturn(500,'缺少字段', []);
            }

            $condition = $req['condition'];

            if(isset($condition['worker_id'])) {
                if($condition['worker_id']==0) {
                    $condition['worker_id'] = session('user_id');
                }
            }

            $result = $case_model->getDataJoinUser($condition);

            $arr = $result['data'];

            $requestArr = [];
            foreach($arr as $key=>$atom) {
                $grade = json_decode($atom['grade'],true);
                $total = 0;
                foreach($grade as $value) {
                    $total += $value;
                }

                $grade_res = '合格';
                if($total < 60) {
                    $grade_res = '不合格';
                }

                $requestArr[$key][0] = $atom['qa_id'];
                $requestArr[$key][1] = $atom['status']==0?'未分发':$atom['status']==1?'已分发':'已完结';
                $requestArr[$key][2] = $atom['creater_name'];
                $requestArr[$key][3] = $atom['created_time'];
                $requestArr[$key][4] = $atom['worker_name'];
                $requestArr[$key][5] = $atom['case_id'];
                $requestArr[$key][6] = $atom['work_line']==1?'在线':'热线';
                $requestArr[$key][7] = $atom['be_test_team'];
                $requestArr[$key][8] = $atom['be_test_servicer'];
                $requestArr[$key][9] = $atom['service_type']==1?'售后服务':$atom['service_type']==2?'运费问题':$atom['service_type']==3?'商家问题':'一般问题';
                $requestArr[$key][10] = $atom['problem_type']==1?'活动客服':$atom['problem_type']==2?'假货客服':$atom['problem_type']==3?'高级客服':'运费客服';
                $requestArr[$key][11] = $atom['comment_result']==-1?'不满意':$atom['comment_result']==0?'一般':'满意';
                $requestArr[$key][12] = $grade_res; // 质检结果
                $requestArr[$key][13] = $total; // 质检总分
                $requestArr[$key][14] = $grade['ceremony'];
                $requestArr[$key][15] = $grade['sysopt'];
                $requestArr[$key][16] = $grade['messagetrans'];
                $requestArr[$key][17] = $grade['pinpoint'];
                $requestArr[$key][18] = $grade['quickhandle'];
            }

            $excel_controller = new ExcelController();
            $result = $excel_controller->genExcel($requestArr);

            if($result['code']==200) {
                return apiReturn(200,'ok',[]);
            }else{
                return apiReturn(500,$result,[]);
            }

        } catch (\Exception $errorException) {
            return apiReturn(500,'系统错误', $errorException->getMessage());
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