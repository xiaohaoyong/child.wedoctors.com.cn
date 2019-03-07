<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/5/21
 * Time: 下午4:48
 */

namespace console\controllers;

use common\models\ChildInfo;
use common\models\Examination;
use common\models\UserParent;
use console\models\Pregnancy;
use OSS\Core\OssException;
use OSS\OssClient;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use udokmeci\yii2beanstalk\BeanstalkController;

class DataUpdateController extends BeanstalkController
{
    public function listenTubes()
    {
        return ['dataupdate'];
    }



    /**
     * 采集数据
     * @param $job Job
     * @return string
     */
    public function actionDataupdate($job='')
    {
        /** @var object $sentData */
        $sentData = $job->getData();
        $hospitalid=$sentData->hospitalid;
        $date=$sentData->date;

        $log=new \common\components\Log('datacallback');
        $log->addLog('异步任务');
        $log->addLog(json_encode([$hospitalid,$date]));

        $bucket= "wedoctorschild";

// object 表示您在下载文件时需要指定的文件名称，如abc/efg/123.jpg。
        $object = $date."-".$hospitalid;
// 指定文件下载路径。
        $localfile = "/tmp/".$hospitalid.".xlsx";
        $options = array(
            OssClient::OSS_FILE_DOWNLOAD => $localfile
        );

        try{
            $ossClient = new OssClient('LTAIteFpOZnX3aoE', 'lYWI5AzSjQiZWBhC2d7Ttt06bnoDFF', 'oss-cn-beijing.aliyuncs.com');

            $ossClient->getObject($bucket, $object, $options);
            $log->addLog("下载成功");
        } catch(OssException $e) {
            $log->addLog("下载失败");
            $log->saveLog();
            return self::DELETE;
        }



        if (empty($localfile) OR !file_exists($localfile)) {

            $log->addLog($localfile."文件不存在");
            $log->saveLog();

            return self::DELETE;
        }
        var_dump('wedoctorschild');exit;

        $objRead = new Xlsx();   //建立reader对象
        $objRead->setReadDataOnly(true);
        $obj = $objRead->load($localfile);  //建立excel对象
        $currSheet = $obj->getSheet(0);   //获取指定的sheet表
        $columnH = $currSheet->getHighestColumn();   //取得最大的列号
        $highestColumnNum = Coordinate::columnIndexFromString($columnH);
        $rowCnt = $currSheet->getHighestRow();   //获取总行数
        $log->addLog("文件解析成功");

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
                    $fields[]=$_column;
                } else {
                    if ($field_index[$_column] && $cellValue !== '') {
                        $rs[$field_index[$_column]] = $cellValue ? (string)$cellValue : 0;
                    }
                }
            }
            if ($_row != 1) {
//                $pregnancy = new \console\models\ExInput();
//                $return = $pregnancy->inputData($rs, $hospitalid);
            }else{
                $table=self::type($fields);
                $log->addLog($table);
                foreach($fields as $k=>$v){
                    $k = array_search($cellValue, $table::$field);
                    if ($k !== false) {
                        $field_index[$_column] = $k;
                    }
                }
                var_dump($field_index);

            }
        }
        $obj->disconnectWorksheets();



        $log->saveLog();


        return self::DELETE;
    }
    public static function type($rs){
        $field_Examination=Examination::$field;
        if(!array_diff($field_Examination,$rs)){
            return "\common\models\Examination";
        }
        $field_Pregnancy=\common\models\Pregnancy::$field;
        if(!array_diff($field_Pregnancy,$rs)){
            return "\common\models\Pregnancy";
        }
        $field_ChildInfo=ChildInfo::$field;
        $field_UserParent=UserParent::$field;
        if(!array_diff($field_UserParent+$field_ChildInfo,$rs)){
            return "\common\models\ChildInfo";
        }

        return '';
    }
}