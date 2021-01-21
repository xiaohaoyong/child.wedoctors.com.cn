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
use common\models\UserLogin;
use common\models\UserParent;
use common\models\WeOpenid;
use console\models\ChildInfoInput;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\base\Controller;
use yii\helpers\ArrayHelper;

class ReturnVisitController extends \yii\console\Controller
{
    public function actionDown($doctorid=0){
        ini_set('memory_limit', '2048M');
        ini_set("max_execution_time", "0");
        set_time_limit(0);
        if($doctorid){
            $this->setDownExcel($doctorid);
        }else {
            $doctor = UserDoctor::find()->all();
            foreach ($doctor as $v) {
                $this->setDownExcel($v->userid);
                echo "\n";
            }
        }
    }
    public function setDownExcel($doctorid){

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getActiveSheet()->mergeCells('A1:O1');
        $field=['姓名','性别','出生日期','内部建档号','健康分类','人群分类','慢病分类','家庭签约','个人签约','身份证号','档案完整度','手机号','联系电话','慢病随访次数','现住址'];
        $sheet->setCellValue('A1', '台帐查询个人档案信息');
        $spreadsheet->getActiveSheet()->getStyle('J')->getNumberFormat() ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);//设置NumberFormat为FORMAT_NUMBER，有其他格式需要可以改成其他格式，如日期：FORMAT_DATE_YYYYMMDD
        $spreadsheet->getActiveSheet()->getStyle('L')->getNumberFormat() ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);//设置NumberFormat为FORMAT_NUMBER，有其他格式需要可以改成其他格式，如日期：FORMAT_DATE_YYYYMMDD


        foreach($field as $k=>$v){
            $key=chr(65+$k);
            $sheet->setCellValue($key.'2', $v);
        }
        $userDoctor=UserDoctor::findOne(['userid'=>$doctorid]);
        $auto=Autograph::find()->select('userid')->where(['doctorid'=>$doctorid])->column();

        if($auto) {
            $data = ChildInfo::find()
                ->andFilterWhere(['in', '`child_info`.`userid`', array_unique($auto)])
                ->asArray()->all();

            foreach ($data as $k => $v) {
                $key=3+$k;
                $userParent = UserParent::findOne(['userid' => $v['userid']]);
                $sheet->setCellValue('A'.$key, $v['name']);
                $sheet->setCellValue('B'.$key, \common\models\ChildInfo::$genderText[$v['gender']]);
                $sheet->setCellValue('C'.$key,date('Y-m-d', $v['birthday']));
                $sheet->setCellValue('H'.$key,'是');
                $sheet->setCellValue('I'.$key,'是');
                $idcard=$v['field27']?$v['field27']:$v['idcard'];
                $sheet->setCellValue('J'.$key,$idcard."\t");
                $phone=UserLogin::getPhone($v['userid']);
                if(!$phone && $userParent){
                    $phone=$userParent->mother_phone;
                }
                $sheet->setCellValue('L'.$key,$phone."\t");
                $sheet->setCellValue('O'.$key,$userParent?$userParent->fieldu46:'');
            }
        }




        $file_name = '上市指令模板'.date('Y-m-d', time()).rand(1000, 9999);
//第一种保存方式
        $writer = new Xlsx($spreadsheet);
        //保存的路径可自行设置
        $file_name =$file_name.".Xlsx";
        $writer->save($file_name);
        return [];
    }
}