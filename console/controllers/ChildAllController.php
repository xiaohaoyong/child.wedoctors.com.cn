<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/6/12
 * Time: 下午4:43
*/

namespace console\controllers;


use common\models\Article;
use common\models\ArticleInfo;
use common\models\ArticleUser;
use common\models\Autograph;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Hospital;
use common\models\UserDoctor;
use common\models\UserParent;
use common\models\WeOpenid;
use console\models\ChildInfoInput;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\base\Controller;
use yii\helpers\ArrayHelper;

class ChildAllController extends Controller
{
    public function actionDown(){
        ini_set('memory_limit', '2048M');
        ini_set("max_execution_time", "0");
        set_time_limit(0);
        $doctor=UserDoctor::find()->all();
        foreach($doctor as $v)
        {
            $this->setDownExcel($v->userid);
            echo "\n";
        }
        //$this->setDownExcel(18491);
    }
    public function setDownExcel($doctorid){

        echo $doctorid;
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
            ->setCellValue('F'.$key1, '出生医学证明号')
            ->setCellValue('G'.$key1, '儿童身份证号')
            ->setCellValue('H'.$key1, '父母')
            ->setCellValue('I'.$key1, '母亲电话')
            ->setCellValue('J'.$key1, '父亲电话')
            ->setCellValue('K'.$key1, '联系人姓名')
            ->setCellValue('L'.$key1, '联系人电话')
            ->setCellValue('M'.$key1, '签约社区')
            ->setCellValue('N'.$key1, '签约时间')
            ->setCellValue('O'.$key1, '签字时间')
            ->setCellValue('P'.$key1, '签约状态')
            ->setCellValue('Q'.$key1, '签约协议')
            ->setCellValue('R'.$key1, '现住址')
            ->setCellValue('S'.$key1, '是否宣教')
            ->setCellValue('T'.$key1, '宣教月龄')
            ->setCellValue('U'.$key1, '宣教内容')
            ->setCellValue('V'.$key1, '宣教时间');

        $userDoctor=UserDoctor::findOne(['userid'=>$doctorid]);
        $auto=DoctorParent::find()->select('parentid')->where(['doctorid'=>$doctorid])->column();

        if($auto) {
            $data = ChildInfo::find()
                ->andFilterWhere(['in', '`child_info`.`userid`' , array_unique($auto)])
                ->asArray()->all();

            foreach ($data as $k => $v) {
                echo "==" . $v['userid'] . "===";
                $e = $v;
                $sign = \common\models\DoctorParent::findOne(['parentid' => $v['userid'], 'level' => 1]);
                $userParent = UserParent::findOne(['userid' => $e['userid']]);

                $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $v['birthday']));
                if ($DiffDate[0]) {
                    $age = $DiffDate[0] . "岁";
                } elseif ($DiffDate[1]) {
                    $age = $DiffDate[1] . "月";
                } else {
                    $age = $DiffDate[2] . "天";
                }
                if ($sign->level != 1) {
                    $return = "未签约";
                } else {
                    if ($userParent->source <= 38) {
                        $return = "已签约未关联";

                    } else {
                        $return = "已签约";
                    }
                }
                $autograph = \common\models\Autograph::findOne(['userid' => $v['userid']]);
                if ($autograph) {
                    $signa = "已签字";
                } else {
                    $signa = "未签字";
                }

                $article = ArticleUser::findAll(['touserid' => $v['userid']]);

                $articleid = ArrayHelper::getColumn($article, 'artid');
                $date = '';
                $child_type = '';
                $title = '';

                if ($article) {
                    foreach ($article as $ak => $av) {
                        $date .= "，" . date('Y-m-d', $av->createtime);
                        $child_type .= "，" . Article::$childText[$av->child_type];
                    }
                    $articleInfo = ArticleInfo::find()->andFilterWhere(['in', 'id', $articleid])->select('title')->column();
                    $title = implode(',', $articleInfo);

                    $is_article = "是";
                } else {
                    $is_article = "否";
                }
                echo "\n";

                $key1 = $k + 2;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $key1, $v['name'])
                    ->setCellValue('B' . $key1, " " . \common\models\User::findOne($v['userid'])->phone)
                    ->setCellValue('C' . $key1, \common\models\ChildInfo::$genderText[$v['gender']])
                    ->setCellValue('D' . $key1, $age)
                    ->setCellValue('E' . $key1, date('Y-m-d', $v['birthday']))
                    ->setCellValue('F' . $key1, $v['field6'])
                    ->setCellValue('G' . $key1, " " . $v['field27'])
                    ->setCellValue('H' . $key1, $userParent->mother || $userParent->father ? $userParent->mother . "/" . $userParent->father : "无")
                    ->setCellValue('I' . $key1, $userParent->mother_phone ? " " . $userParent->mother_phone : "无")
                    ->setCellValue('J' . $key1, $userParent->father_phone ? " " . $userParent->father_phone : "无")
                    ->setCellValue('K' . $key1, $userParent->field11 ? $userParent->field11 : "无")
                    ->setCellValue('L' . $key1, $userParent->field12 ? " " . $userParent->field12 : "无")
                    ->setCellValue('M' . $key1, $sign->level == 1 ? \common\models\UserDoctor::findOne(['userid' => $sign->doctorid])->name : "--")
                    ->setCellValue('N' . $key1, $sign->level == 1 ? date('Y-m-d H:i', $sign->createtime) : "无")
                    ->setCellValue('O' . $key1, date('Y-m-d H:i',  Autograph::find()->where(['userid'=>$userParent->userid])->orderBy('id desc')->one()->createtime))
                    ->setCellValue('P' . $key1, $return)
                    ->setCellValue('Q' . $key1, $signa)
                    ->setCellValue('R' . $key1, $userParent->fieldu46)
                    ->setCellValue('S' . $key1, $is_article)
                    ->setCellValue('T' . $key1, $child_type)
                    ->setCellValue('U' . $key1, $title)
                    ->setCellValue('V' . $key1, $date);

            }
            // $objPHPExcel->setActiveSheetIndex(0);


            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(dirname(__ROOT__) . "/static/" . $userDoctor->hospitalid . "-all.xlsx");
            echo "true";
        }
        return [];
    }




    public function actionInput(){
        ini_set('memory_limit','2048M');
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set("max_execution_time", "0");
        set_time_limit(0);

        //        ChildInfo::updateAll(['doctorid'=>0],'source ='.$hospitalid);$this->getExcel("data/$hospitalid.xlsx",$hospitalid);exit;
        $file_list=glob("data/*.xlsx");
        foreach($file_list as $fk=>$fv) {
            preg_match("#\d+#", $fv, $m);
            if ($hospitalid = $m[0]) {
              // ChildInfo::updateAll(['doctorid'=>$hospitalid],'source ='.$hospitalid);

                $this->getExcel($fv,$hospitalid);
            }
        }

    }

    public function getExcel($file,$hospitalid){
        $file = iconv("utf-8", "gb2312", $file);   //转码
        if (empty($file) OR !file_exists($file)) {
            die('file not exists!');
        }



        $objRead = new Xlsx();   //建立reader对象
        if (!$objRead->canRead($file)) {
            $objRead = new \PHPExcel_Reader_Excel5();
            if (!$objRead->canRead($file)) {
                die('No Excel!');
            }
        }

        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        $obj = $objRead->load($file);  //建立excel对象
        $currSheet = $obj->getSheet(0);   //获取指定的sheet表
        $columnH = $currSheet->getHighestColumn();   //取得最大的列号

        $a1 = array_search($columnH[0], $cellName);
        $a2 = array_search($columnH[1], $cellName);
        $columnCnt=85;
        $rowCnt = $currSheet->getHighestRow();   //获取总行数
        ChildInfo::updateAll(['admin'=>0],'source ='.$hospitalid);

        $data = array();
        for ($_row = 1; $_row <= $rowCnt; $_row++) {  //
            $rs=[];
            for ($_column = 0; $_column <= $columnCnt; $_column++) {
                $a="";
                $b=$_column%26;
                $c=floor($_column/26);
                if($c>0){
                    $a=chr($c-1+65);
                }
                $a=$a.chr($b+65);

                $cellId = $a . $_row;
                $cellValue = $currSheet->getCell($cellId)->getValue();
                $rs[$_column]=$cellValue?(string)$cellValue:0;
            }
            if($rs[1]!='本市' && $rs[1]!='外地')
            {
                continue;
            }
            $ChildInfoInput=new ChildInfoInput();
            $ChildInfoInput->hospitalid=$hospitalid;
            $ChildInfoInput->inputData($rs);
        }
        return [];
    }
}