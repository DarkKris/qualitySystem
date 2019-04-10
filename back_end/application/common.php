<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

const CODE_SUCCESS = 0 , CODE_ERROR = 1 , CODE_TEST = -1;

function apiReturn($code,$message,$data,$http_code=200) {
    return json([
        'code'=>$code,
        'message'=>$message,
        'data'=>$data
    ],$http_code);
}