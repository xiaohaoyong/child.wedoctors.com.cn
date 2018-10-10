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
use common\models\ChildInfo;
use hospital\models\User;
use hospital\models\user\ChildInfoSearchModel;
use hospital\models\user\DoctorParent;
use hospital\models\user\UserParent;
use yii\helpers\ArrayHelper;

class DownController extends BaseController
{
    public function actionChildnew(){


        $doctor=\common\models\UserDoctor::findOne(['hospitalid'=>\Yii::$app->user->identity->hospital]);


        //以只读和二进制模式打开文件
        $file=dirname(__ROOT__) . "/static/".\Yii::$app->user->identity->hospital.".xlsx";
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }
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
            ->setCellValue('L'.$key1, '居委会')
            ->setCellValue('M'.$key1, '签约时间')
            ->setCellValue('N'.$key1, '签约状态')
            ->setCellValue('O'.$key1, '是否宣教')
            ->setCellValue('P'.$key1, '宣教月龄')
            ->setCellValue('Q'.$key1, '宣教内容')
            ->setCellValue('R'.$key1, '宣教时间');
//写入内容
        foreach($dataProvider->query->limit(500)->all() as $k=>$e) {
            $v=$e->toArray();
            $sign = \common\models\DoctorParent::findOne(['parentid'=>$v['userid'],'level'=>1]);
            $userParent = UserParent::findOne(['userid'=>$v['userid']]);

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
                if($userParent->source<=38){
                    $return="已签约未关联";

                }else {
                    $return = "已签约";
                }
            }

            $article=ArticleUser::findAll(['touserid'=>$v['userid']]);
            $articleid=ArrayHelper::getColumn($article,'id');

            $date='';
            $child_type='';
            $title='';

            if($article) {
                foreach ($article as $ak => $av) {
                    $date.="，".date('Y-m-d',$av->createtime);
                    $child_type.="，".Article::$childText[$av->child_type];
                    //$articleInfo=ArticleInfo::findOne(['id'=>$av->artid]);
                    //$title.=$articleInfo?"，".$articleInfo->title:"";
                }
                $articleInfo=ArticleInfo::find()->andFilterWhere(['in','id',$articleid])->select('title')->column();
                $title=implode(',',$articleInfo);

                $is_article="是";
            }else{
                $is_article="否";
            }
            echo "\n";

            $key1 = $k + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $key1, $v['name'])
                ->setCellValue('B' . $key1, " ".\common\models\User::findOne($v['userid'])->phone)
                ->setCellValue('C' . $key1, \common\models\ChildInfo::$genderText[$v['gender']])
                ->setCellValue('D' . $key1, $age)
                ->setCellValue('E' . $key1, date('Y-m-d', $v['birthday']))
                ->setCellValue('F' . $key1, $userParent->mother || $userParent->father?$userParent->mother."/".$userParent->father:"无")
                ->setCellValue('G' . $key1, $userParent->mother_phone ? " ".$userParent->mother_phone : "无")
                ->setCellValue('H' . $key1, $userParent->father_phone ?  " ".$userParent->father_phone  : "无")
                ->setCellValue('I' . $key1, $userParent->field11 ?  $userParent->field11 : "无")
                ->setCellValue('J' . $key1, $userParent->field12 ? " ".$userParent->field12 : "无")
                ->setCellValue('K' . $key1, $sign->level==1 ? \common\models\UserDoctor::findOne(['userid'=>$sign->doctorid])->name : "--")
                ->setCellValue('L' . $key1, $v['field50'])
                ->setCellValue('M' . $key1, $sign->level == 1 ? date('Y-m-d H:i', $sign->createtime) : "无")
                ->setCellValue('N' . $key1, $return)
                ->setCellValue('O' . $key1, $is_article)
                ->setCellValue('P' . $key1, $child_type)
                ->setCellValue('Q' . $key1, $title)
                ->setCellValue('R' . $key1, $date);
        }
        // $objPHPExcel->setActiveSheetIndex(0);

        ob_end_clean();
        ob_start();

        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="儿童中医药健康管理服务表-'.date("Y年m月j日").'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
    public function actionArticle(){
        ini_set('memory_limit', '2048M');
        ini_set("max_execution_time", "0");
        ini_set('zlib.output_compression', 'Off');
        set_time_limit(0);

        $params=\Yii::$app->request->queryParams;
        $searchModel = new ChildInfoSearchModel();
        $dataProvider = $searchModel->search($params);
        $hospitalid=\Yii::$app->user->identity->hospital;
        $filename='article-' .$hospitalid. date("Ymd") . '.zip';

        $zipname = dirname(__ROOT__)."/static/childEducation/".$filename;


        $zip = new \ZipArchive();
        $res = $zip->open($zipname, \ZipArchive::OVERWRITE | \ZipArchive::CREATE);

        if ($res === TRUE) {
            foreach ($dataProvider->query->all() as $k => $e) {
                $n=ceil($e->id%100);
                $file=dirname(__ROOT__)."/static/childEducation/".$n."/".$e->id.".xlsx";
                if(file_exists($file)) {
                    $child=ChildInfo::findOne($e->id);
                    $zip->addFile($file, $child->name . ".xlsx");
                }
            }
            $zip->close();
            //Begin writing headers
            header("Pragma: public");
            header("Expires: 0");
            header("Connection: keep-alive");

            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header("Content-Description: File Transfer");

            //Use the switch-generated Content-Type
            header("Content-Type: application/zip");

            //Force the download
            $header="Content-Disposition: attachment; filename=中医儿童健康管理宣教记录.zip;";
            header($header );
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".filesize($zipname));
            ob_end_clean();
            @readfile($zipname);
        }
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