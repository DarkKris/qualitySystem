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
//            $arr = input('post.arr');

            $excel = new \PHPExcel();
            $excel->getProperties()
                ->setCreator('质检管理系统')
                ->setTitle("filter_data");

            // set header
            $excel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(0,1,"质检单ID")
                ->setCellValueByColumnAndRow(1,1,"状态")
                ->setCellValueByColumnAndRow(2,1,"创建人")
                ->setCellValueByColumnAndRow(3,1,"创建时间")
                ->setCellValueByColumnAndRow(4,1,"质检员")
                ->setCellValueByColumnAndRow(5,1,"caseID")
                ->setCellValueByColumnAndRow(6,1,"业务线")
                ->setCellValueByColumnAndRow(7,1,"被质检团队（客服团队）")
                ->setCellValueByColumnAndRow(8,1,"被质检人（客服）")
                ->setCellValueByColumnAndRow(9,1,"客服类型")
                ->setCellValueByColumnAndRow(10,1,"问题类型")
                ->setCellValueByColumnAndRow(11,1,"评价结果")
                ->setCellValueByColumnAndRow(12,1,"质检结果")
                ->setCellValueByColumnAndRow(13,1,"质检得分")
                ->setCellValueByColumnAndRow(14,1,"礼仪规范")
                ->setCellValueByColumnAndRow(15,1,"系统操作规范")
                ->setCellValueByColumnAndRow(16,1,"信息传递规范")
                ->setCellValueByColumnAndRow(17,1,"精准定位问题")
                ->setCellValueByColumnAndRow(18,1,"快速处理问题");

            // set background
            $excel->getActiveSheet()->getStyle( 'C1')->getFill()->getStartColor()->setARGB('FFF5D3E5');
            $excel->getActiveSheet()->getStyle( 'E1')->getFill()->getStartColor()->setARGB('FFFBF1CC');
            $excel->getActiveSheet()->getStyle( 'F1:L1')->getFill()->getStartColor()->setARGB('FFDBE0F1');

            $row = 2;
            foreach($arr as $value) {
                $col = 0;
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
            $filename = 'filter_data.xls';
            $contents = file_get_contents($dir.'/'.$filename);
            $file_size = filesize($dir.'/'.$filename);

            // download
//            header('Content-type: application/octet-stream, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=GBK;');
//            header('Accept-Range: bytes');
//            header("Accept-Length: $file_size");
//            header("Content-Disposition: attachment;filename=".$filename);
//            header('Cache-Control: max-age=0');

            return ['code'=>200,'message'=>'ok','data'=>['filename'=>$filename,'obj'=>$excel]];
        } catch(\PHPExcel_Writer_Exception $e) {
            return ['code'=>200,'message'=>$e->getMessage(),'data'=>[]];
        } catch(\PHPExcel_Exception $e1) {
            return ['code'=>200,'message'=>$e1->getMessage(),'data'=>[]];
        }
    }
}