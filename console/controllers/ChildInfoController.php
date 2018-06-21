<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/6/12
 * Time: 下午4:43
*/

namespace console\controllers;


use common\models\ChildInfo;
use common\models\WeOpenid;
use console\models\ChildInfoInput;
use yii\base\Controller;

class ChildInfoController extends Controller
{



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
               ChildInfo::updateAll(['doctorid'=>$hospitalid],'source ='.$hospitalid);

                //$this->getExcel($fv,$hospitalid);
            }
        }

    }

    public function getExcel($file,$hospitalid){
        $file = iconv("utf-8", "gb2312", $file);   //转码
        if (empty($file) OR !file_exists($file)) {
            die('file not exists!');
        }
        $objRead = new \PHPExcel_Reader_Excel2007();   //建立reader对象
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
        ChildInfo::updateAll(['doctorid'=>0],'source ='.$hospitalid);

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