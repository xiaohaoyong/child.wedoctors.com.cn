<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/5/24
 * Time: 下午4:24
 */

namespace hospital\controllers;


use common\models\Article;
use common\models\ArticleInfo;
use common\models\ArticleUser;
use hospital\models\User;
use hospital\models\user\ChildInfoSearchModel;
use hospital\models\user\DoctorParent;
use hospital\models\user\UserParent;

class DownController extends BaseController
{
    public function actionChild(){
        ini_set('memory_limit', '2048M');
        ini_set("max_execution_time", "0");
        set_time_limit(0);


        $params=\Yii::$app->request->queryParams;
        $searchModel = new ChildInfoSearchModel();
        $dataProvider = $searchModel->search($params);


        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties();

        //设置A3单元格为文本
        $objPHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $objPHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $objPHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $objPHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $key1 = 1;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$key1, '姓名')
            ->setCellValue('B'.$key1, '联系电话')
            ->setCellValue('C'.$key1, '性别')
            ->setCellValue('D'.$key1, '年龄')
            ->setCellValue('E'.$key1, '出生日期')
            ->setCellValue('F'.$key1, '父母')
            ->setCellValue('G'.$key1, '母亲电话')
            ->setCellValue('H'.$key1, '父亲电话')
            ->setCellValue('I'.$key1, '联系人姓名')
            ->setCellValue('J'.$key1, '联系人电话')
            ->setCellValue('K'.$key1, '签约社区')
            ->setCellValue('L'.$key1, '签约时间')
            ->setCellValue('M'.$key1, '签约状态')
            ->setCellValue('N'.$key1, '是否宣教')
            ->setCellValue('O'.$key1, '宣教月龄')
            ->setCellValue('P'.$key1, '宣教内容')
            ->setCellValue('Q'.$key1, '宣教时间');
//写入内容
        foreach($dataProvider->query->limit(500)->asArray()->all() as $k=>$v) {
            $e=$v;
            $sign = \common\models\DoctorParent::findOne(['parentid'=>$v['userid'],'level'=>1]);
            $userParent = UserParent::findOne(['userid'=>$e->userid]);

            $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $v['birthday']));
            if($DiffDate[0]) {
                $age=$DiffDate[0]."岁";
            }elseif($DiffDate[1]){
                $age=$DiffDate[1]."月";
            }else{
                $age=$DiffDate[2]."天";
            }
            if($sign->level!=1)
            {
                $return="未签约";
            }else{
                if($e['source']<=38){
                    $return="已签约未关联";

                }else {
                    $return = "已签约";
                }
            }

            $article=ArticleUser::findAll(['touserid'=>$v['userid']]);

            $date='';
            $child_type='';
            $title='';

            if($article) {
                foreach ($article as $ak => $av) {
                    $date.="，".date('Y-m-d',$av->createtime);
                    $child_type.="，".Article::$childText[$av->child_type];
                    $articleInfo=ArticleInfo::findOne(['id'=>$av->artid]);
                    $title.=$articleInfo?"，".$articleInfo->title:"";
                }
                $is_article="是";
            }else{
                $is_article="否";
            }


            $key1 = $k + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $key1, $v['name'])
                ->setCellValue('B' . $key1, " ".\common\models\User::findOne($v['userid'])->phone)
                ->setCellValue('C' . $key1, \common\models\ChildInfo::$genderText[$v['gender']])
                ->setCellValue('D' . $key1, $age)
                ->setCellValue('E' . $key1, date('Y-m-d', $v['birthday']))
                ->setCellValue('F' . $key1, $v['mother'] || $v['father']?$v['mother']."/".$v['father']:"无")
                ->setCellValue('G' . $key1, $v['mother_phone'] ? " ".$v['mother_phone'] : "无")
                ->setCellValue('H' . $key1, $v['father_phone'] ?  " ".$v['father_phone'] : "无")
                ->setCellValue('I' . $key1, $v['field11'] ? $v['field11'] : "无")
                ->setCellValue('J' . $key1, $v['field12'] ? " ".$v['field12'] : "无")
                ->setCellValue('K' . $key1, $sign->level==1 ? \common\models\UserDoctor::findOne(['userid'=>$sign->doctorid])->name : "--")
                ->setCellValue('L' . $key1, $sign->level == 1 ? date('Y-m-d H:i', $sign->createtime) : "无")
                ->setCellValue('M' . $key1, $return)
                ->setCellValue('N' . $key1, $is_article)
                ->setCellValue('O' . $key1, $child_type)
                ->setCellValue('P' . $key1, $title)
                ->setCellValue('Q' . $key1, $date);
        }
        // $objPHPExcel->setActiveSheetIndex(0);

        ob_end_clean();
        ob_start();

        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="儿童中医药健康管理服务表-'.date("Y年m月j日").'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
    public function actionDownload($childid=0)
    {

        $chileInfo=ChildInfo::findOne($childid);
        $excel=new \PHPExcel();
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

        $excel->getActiveSheet()->setCellValue('A1',$chileInfo->name.'-儿童中医药健康管理服务表');
        $excel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(24);
        $excel->setActiveSheetIndex(0)->getStyle('A1:N6')->getFont()->setName('宋体');
        $excel->getActiveSheet()->getStyle('A2:N6')->getAlignment()->setWrapText(true);
        $excel->getActiveSheet()->getStyle('A1:A6')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->setActiveSheetIndex(0)->getStyle('A2:N6')->getFont()->setSize(16);


        $excel->setActiveSheetIndex(0)->setCellValue('A2','月龄');
        $excel->setActiveSheetIndex(0)->setCellValue('A3','随访日期');
        $excel->setActiveSheetIndex(0)->setCellValue('A4','中医药健康管理服务');
        $excel->setActiveSheetIndex(0)->setCellValue('A5','下次随访日期');
        $excel->setActiveSheetIndex(0)->setCellValue('A6','随访医生签名');
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $excel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
        $excel->getActiveSheet()->getRowDimension('3')->setRowHeight(30);
        $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(30);
        $excel->getActiveSheet()->getRowDimension('6')->setRowHeight(30);

        $excel->getActiveSheet()->getStyle('A1:N6')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);;

        $chile_type=Article::$childText;

        $ascii=66;
        foreach($chile_type as $k=>$v)
        {
            $articleUser=ArticleUser::find()->where(['childid'=>$childid,'child_type'=>$k])->all();



            $t = chr($ascii);
            $excel->setActiveSheetIndex(0)->setCellValue($t."2", $v);

            if($articleUser) {
                $articleid=ArticleUser::find()->select('artid')->where(['childid'=>$childid,'child_type'=>$k])->column();
                $article=ArticleInfo::find()->select('title')->where(['in','id',$articleid])->column();
                foreach($article as $ak=>$av)
                {
                    $article[$ak]=($ak+1).".".$av;
                }
                $excel->setActiveSheetIndex(0)->setCellValue($t."3", date('Y/m/d',$articleUser[0]->createtime));
                $excel->setActiveSheetIndex(0)->setCellValue($t."4", implode("\n",$article));
                $excel->setActiveSheetIndex(0)->setCellValue($t."5", '');
                $excel->setActiveSheetIndex(0)->setCellValue($t."6", UserDoctor::findOne(['userid'=>$articleUser[0]->userid])->name);

            }
            $excel->getActiveSheet()->getColumnDimension($t)->setWidth(25);
            $ascii++;
        }

        $excel->getActiveSheet()->getStyle('A2:N2')
            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        ob_end_clean();
        ob_start();

        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.$chileInfo->name.'-儿童中医药健康管理服务表-'.date("Y年m月j日").'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($excel,'Excel5');
        $objWriter->save('php://output');
    }
}