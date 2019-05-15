<?php
/**
 * Created by PhpStorm.
 * User: DarkKris
 * Date: 2019/4/26
 * Time: 下午11:23
 */
namespace app\index\validate;

use think\Validate;

class CaseValidate extends Validate {
    protected $rule = [
        'case_id' => 'require|number|length:8',             // case_id
        'work_line' => 'require|integer|length:1',          // 业务线
        'be_test_team' => 'require',                        // 被质检团队
        'problem_type' => 'require|integer|length:1',       // 问题类型
        'service_type' => 'require|integer|length:1',       // 客服类型
        'comment_result' => 'require',                      // 评价结果
        'handout_type' => 'require|integer|length:1',       // 分配方式
        'worker_id' => 'require',                           // 质检员id
        'qa_id' => 'require',                               // 质检单id
        'ceremony' => 'require|integer|between:0,10',       // 打分项：礼仪规范
        'sysopt' => 'require|integer|between:0,20',         // 打分项：系统操作规范
        'messagetrans' => 'require|integer|between:0,20',   // 打分项：信息传递规范
        'pinpoint' => 'require|integer|between:0,20',       // 打分项：精准定位问题
        'quickhandle' => 'require|integer|between:0,30',    // 打分项：快速处理问题
        'handout_total' => 'require|integer',               // 分配质检单总数
    ];

    protected $message = [
        'case_id.require' => '缺少字段',
        'case_id.number' => '格式错误',
        'case_id.length' => '格式错误',
        'work_line.require' => '缺少字段',
        'work_line.integer' => '格式错误',
        'work_line.length' => '格式错误',
        'be_test_team.require' => '缺少字段',
        'problem_type.require' => '缺少字段',
        'problem_type.integer' => '格式错误',
        'problem_type.length' => '格式错误',
        'service_type.require' => '缺少字段',
        'service_type.integer' => '格式错误',
        'service_type.length' => '格式错误',
        'comment_result.require' => '缺少字段',
        'handout_type.require' => '缺少字段',
        'handout_type.integer' => '格式错误',
        'handout_type.length' => '格式错误',
        'worker_id.require' => '缺少字段',
        'qa_id.require' => '缺少字段',
        'ceremony.require' => '缺少字段',
        'ceremony.integer' => '格式错误',
        'ceremony.between' => '数字超界',
        'sysopt.require' => '缺少字段',
        'sysopt.integer' => '格式错误',
        'sysopt.between' => '数字超界',
        'messagetrans.require' => '缺少字段',
        'messagetrans.integer' => '格式错误',
        'messagetrans.between' => '数字超界',
        'pinpoint.require' => '缺少字段',
        'pinpoint.integer' => '格式错误',
        'pinpoint.between' => '数字超界',
        'quickhandle.require' => '缺少字段',
        'quickhandle.integer' => '格式错误',
        'quickhandle.between' => '数字超界',
        'handout_total.require' => '缺少字段',
        'handout_total.integer' => '格式错误',
    ];

    protected $scene = [
        'filterCount' => ['worker_line','be_test_team','problem_type','service_type','comment_result'],
        'filterHandout' => ['worker_line','be_test_team','problem_type','service_type','comment_result','handout_type','handout_total'],
        'searchByID' => ['case_id'],
        'checkPrivilege' => ['worker_id','qa_id'],
        'markCase' => ['ceremony', 'sysopt', 'messagetrans', 'pinpoint', 'quickhandle', 'qa_id'],
        'getCaseMsg' => ['qa_id'],
    ];
}