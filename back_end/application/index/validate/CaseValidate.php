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
        'case_id' => 'require|number|length:8',         // case_id
        'work_line' => 'require|number|length:1',       // 业务线
        'be_test_team' => 'require',                    // 被质检团队
        'problem_type' => 'require|number|length:1',    // 问题类型
        'service_type' => 'require|number|length:1',    // 客服类型
        'comment_result' => 'require',                  // 评价结果
        'handout_type' => 'require|number|length:1',    // 分配方式
    ];

    protected $message = [
        'case_id.reuire' => '缺少字段',
        'case_id.number' => '格式错误',
        'case_id.length' => '格式错误',
        'work_line.require' => '缺少字段',
        'work_line.number' => '格式错误',
        'work_line.length' => '格式错误',
        'be_test_team.require' => '缺少字段',
        'problem_type.require' => '缺少字段',
        'problem_type.number' => '格式错误',
        'problem_type.length' => '格式错误',
        'service_type.require' => '缺少字段',
        'service_type.number' => '格式错误',
        'service_type.length' => '格式错误',
        'comment_result.require' => '缺少字段',
        'handout_type.require' => '缺少字段',
        'handout_type.number' => '格式错误',
        'handout_type.length' => '格式错误',
    ];

    protected $scene = [
        'filterCount' => ['worker_line','be_test_team','problem_type','service_type','comment_result'],
        'filterHandout' => ['worker_line','be_test_team','problem_type','service_type','comment_result','handout_type'],
        'searchByID' => ['case_id'],
    ];
}