<?php
/**
 * Created by PhpStorm.
 * User: DarkKris
 * Date: 2019/5/17
 * Time: 下午5:19
 */
namespace app\index\controller;

use think\Controller;
use think\facade\Env;

require_once Env::get('root_path') . "extend/PHPExcel.php";

class ExcelController extends Controller {
    public function genExcel(array $arr) {
        try {
            $excel = new \PHPExcel();
            $excel->getProperties()
                ->setCreator('质检管理系统')
                ->setTitle("质检管理系统筛选数据");

            // todo set header

            $row = 1;
            foreach($arr as $value) {
                $col = 1;
                foreach($value as $info) {
                    $excel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow($col,$row,$info);
                    $col++;
                }
                $row++;
            }

            $dir = Env::get('root_path').'excel';

            if( !file_exists($dir) ) {
                mkdir($dir);
                chmod($dir,0755);
            }


            (new \PHPExcel_Writer_Excel2007($excel))->save($dir . '/filter_data.xls');
            $filename = '质检管理系统筛选数据.xls';
            $contents = file_get_contents($dir.'/'.$filename);
            $file_size = filesize($dir.'/'.$filename);

            header('Content-type: application/octet-stream;charset=utf-8');
            header('Accept-Range: bytes');
            header("Accept-Length: $file_size");
            header("Content-Disposition: attachment;filename=".$filename);

            return ['code'=>200,'message'=>'ok','data'=>$contents];
        } catch(\PHPExcel_Writer_Exception $e) {
            return ['code'=>200,'message'=>$e->getMessage(),'data'=>[]];
        } catch(\PHPExcel_Exception $e1) {
            return ['code'=>200,'message'=>$e->getMessage(),'data'=>[]];
        }
    }
}