<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/9/5
 * Time: 上午10:55
 */

namespace console\controllers;


use common\models\Article;
use common\models\ArticleInfo;
use common\models\ArticleUser;
use common\models\ChildInfo;
use common\models\UserDoctor;
use yii\base\Controller;

class ArticleUserController extends Controller
{
    public function actionUser(){
        ini_set('memory_limit', '2048M');
        ini_set("max_execution_time", "0");
        set_time_limit(0);
        $users=ArticleUser::find()->groupBy('childid')->orderBy('childid desc')->all();
        foreach($users as $uk=>$uv) {
            $childid=$uv->childid;
            $chileInfo = ChildInfo::findOne($childid);
            $excel = new \PHPExcel();
            $excel->setActiveSheetIndex(0);


            //报表头的输出
            $excel->getActiveSheet()->mergeCells('A1:N1');
            //设置居中
            $excel->getActiveSheet()->getStyle('A1:N1')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('A2:N6')
                ->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中

            $excel->getActiveSheet()->getStyle('A2:N3')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->getStyle('A5:N6')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->setCellValue('A1', $chileInfo->name . '-儿童中医药健康管理服务表');
            $excel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(24);
            $excel->setActiveSheetIndex(0)->getStyle('A1:N6')->getFont()->setName('宋体');
            $excel->getActiveSheet()->getStyle('A2:N6')->getAlignment()->setWrapText(true);
            $excel->getActiveSheet()->getStyle('A1:A6')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->setActiveSheetIndex(0)->getStyle('A2:N6')->getFont()->setSize(16);


            $excel->setActiveSheetIndex(0)->setCellValue('A2', '月龄');
            $excel->setActiveSheetIndex(0)->setCellValue('A3', '随访日期');
            $excel->setActiveSheetIndex(0)->setCellValue('A4', '中医药健康管理服务');
            $excel->setActiveSheetIndex(0)->setCellValue('A5', '下次随访日期');
            $excel->setActiveSheetIndex(0)->setCellValue('A6', '随访医生签名');
            $excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $excel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
            $excel->getActiveSheet()->getRowDimension('3')->setRowHeight(30);
            $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(30);
            $excel->getActiveSheet()->getRowDimension('6')->setRowHeight(30);

            $excel->getActiveSheet()->getStyle('A1:N6')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);;

            $chile_type = Article::$childText;

            $ascii = 66;
            foreach ($chile_type as $k => $v) {
                $articleUser = ArticleUser::find()->where(['childid' => $childid, 'child_type' => $k])->all();


                $t = chr($ascii);
                $excel->setActiveSheetIndex(0)->setCellValue($t . "2", $v);

                if ($articleUser) {
                    $articleid = ArticleUser::find()->select('artid')->where(['childid' => $childid, 'child_type' => $k])->column();
                    $article = ArticleInfo::find()->select('title')->where(['in', 'id', $articleid])->column();
                    foreach ($article as $ak => $av) {
                        $article[$ak] = ($ak + 1) . "." . $av;
                    }
                    $excel->setActiveSheetIndex(0)->setCellValue($t . "3", date('Y/m/d', $articleUser[0]->createtime));
                    $excel->setActiveSheetIndex(0)->setCellValue($t . "4", implode("\n", $article));
                    $excel->setActiveSheetIndex(0)->setCellValue($t . "5", '');
                    $excel->setActiveSheetIndex(0)->setCellValue($t . "6", UserDoctor::findOne(['userid' => $articleUser[0]->userid])->name);

                }
                $excel->getActiveSheet()->getColumnDimension($t)->setWidth(25);
                $ascii++;
            }

            $excel->getActiveSheet()->getStyle('A2:N2')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objWriter= \PHPExcel_IOFactory::createWriter($excel,'Excel2007');
            $n=ceil($childid%100);

            if (!file_exists(dirname(__ROOT__)."/static/childEducation/".$n)){
                mkdir (dirname(__ROOT__)."/static/childEducation/".$n,0777,true);
            }
            $objWriter->save(dirname(__ROOT__)."/static/childEducation/".$n."/".$childid.".xlsx");
        }
    }


}