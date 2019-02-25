<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/6/12
 * Time: 下午4:43
*/

namespace console\controllers;


use common\models\ChildInfo;
use common\models\Examination;
use common\models\WeOpenid;
use console\models\ChildInfoInput;
use console\models\ExInput;
use yii\base\Controller;

class ExController extends Controller
{

    public function actionInput(){
        ini_set('memory_limit','2048M');
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set("max_execution_time", "0");
        set_time_limit(0);

        //        ChildInfo::updateAll(['doctorid'=>0],'source ='.$hospitalid);$this->getExcel("data/$hospitalid.xlsx",$hospitalid);exit;
        $file_list=glob("data/ex/*.xlsx");
        foreach($file_list as $fk=>$fv) {
            preg_match("#\d+#", $fv, $m);
            $hospitalid = substr($m[0], 0, 6);
            echo $fv."==";
            $this->getExcel($fv,$hospitalid);
        }

    }

    public function getExcel($file,$hospitalid){
        //$file = iconv("utf-8", "gb2312", $file);   //转码
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


        $obj = $objRead->load($file);  //建立excel对象
        $currSheet = $obj->getSheet(0);   //获取指定的sheet表
        $columnH = $currSheet->getHighestColumn();   //取得最大的列号
        $highestColumnNum = \PHPExcel_Cell::columnIndexFromString($columnH);
        $rowCnt = $currSheet->getHighestRow();   //获取总行数

        $field_index=[];
        for ($_row = 1; $_row <= $rowCnt; $_row++) {  //
            $rs = [];
            for ($_column = 0; $_column < $highestColumnNum; $_column++) {
                $a = "";
                $b = $_column % 26;
                $c = floor($_column / 26);
                if ($c > 0) {
                    $a = chr($c - 1 + 65);
                }
                $a = $a . chr($b + 65);
                $cellId = $a . $_row;
                $cellValue = $currSheet->getCell($cellId)->getValue();
                if ($_row == 1) {
                    $k = array_search($cellValue, Examination::$field);
                    if ($k !== false) {
                        $field_index[$_column] = 'field' . $k;
                    }
                } else {
                    if ($field_index[$_column] && $cellValue !== '') {
                        $rs[$field_index[$_column]] = $cellValue ? (string)$cellValue : 0;
                    }
                }
            }
            if ($_row != 1) {
                $pregnancy = new \console\models\ExInput();
                $return = $pregnancy->inputData($rs, $hospitalid);
                if ($return == 'jump') {
                    return [];
                }
            }
        }
        $obj->disconnectWorksheets();
        return [];
    }


}