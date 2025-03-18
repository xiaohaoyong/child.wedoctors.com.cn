<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/5/21
 * Time: 下午4:48
 */

namespace console\controllers;

use common\models\ChildInfo;
use common\models\DataUpdateRecord;
use common\models\Doctors;
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
    public function actionDataupdate($job = '')
    {
        /** @var object $sentData */
        $sentData = $job->getData();
        $hospitalid = $sentData->hospitalid;
        $date = $sentData->date;
        $id = $sentData->id;

        $dur = DataUpdateRecord::findOne($id);
        $dur->state = 1;
        $dur->save();

        $log = new \common\components\Log('datacallback', true);
        $log->addLog('异步任务');
        $log->addLog(json_encode([$hospitalid, $date]));

        $bucket = "wedoctorschild";
        $ossClient = new OssClient(\Yii::$app->params['aliak'], \Yii::$app->params['aliaks'], 'oss-cn-beijing.aliyuncs.com');
        $prefix = $hospitalid . '/';
        $delimiter = '/';
        $nextMarker = '';
        $maxkeys = 100;
        $options = array(
            'delimiter' => $delimiter,
            'prefix' => $prefix,
            'max-keys' => $maxkeys,
            'marker' => $nextMarker,
        );
        $listObjectInfo = $ossClient->listObjects($bucket, $options);
        $objectList = $listObjectInfo->getObjectList(); // object list
        if ($objectList[0]) {
            $object = $objectList[0]->getKey();
            // 指定文件下载路径。
            $localfile = "/tmp/" . $hospitalid . ".xlsx";
            $options = array(
                OssClient::OSS_FILE_DOWNLOAD => $localfile
            );
            $ossClient->getObject($bucket, $object, $options);
            $log->addLog("下载成功");
            $log->saveLog();
        } else {
            $log->addLog("未找到文件");
            $log->saveLog();
            return self::DELETE;
        }
        $log->addLog("下载成功121212");


        if (empty($localfile) or !file_exists($localfile)) {

            $log->addLog($localfile . "文件不存在");
            $log->saveLog();

            return self::DELETE;
        }
        $log->addLog("下载成功2");

        ini_set('memory_limit', '1500M');

        $objRead = new Xlsx();   //建立reader对象
        $log->addLog("下载成功3");

        $objRead->setReadDataOnly(true);
        $log->addLog("下载成功4");

        $obj = $objRead->load($localfile);  //建立excel对象
        var_dump($obj);exit;
        $currSheet = $obj->getSheet(0);   //获取指定的sheet表
        $columnH = $currSheet->getHighestColumn();   //取得最大的列号
        $highestColumnNum = Coordinate::columnIndexFromString($columnH);
        $rowCnt = $currSheet->getHighestRow();   //获取总行数
        $log->addLog("文件解析成功");

        $dur->state = 2;
        $dur->num = $rowCnt - 1;
        $dur->save();

        $field_index = [];
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
                    $fields[$_column] = $cellValue;
                } else {
                    if (isset($field_index[$_column]) && $cellValue !== '') {
                        $rs[$field_index[$_column]] = $cellValue ? (string)$cellValue : 0;
                    }
                }
            }
            if ($_row != 1) {
                var_dump($rs);
                $return = $table::inputData($rs, $hospitalid);
                if ($return == 2) {
                    $dur->new_num = $dur->new_num + 1;
                    $dur->save();
                }
            } else {
                $table = self::type($fields, $dur);
                if ($table) {
                    $log->addLog($table);
                    foreach ($fields as $k => $v) {
                        $fk = array_search($v, $table::$field);
                        if ($fk !== false) {
                            if ($table != '\common\models\ChildInfo') {
                                $field_index[$k] = 'field' . $fk;
                            } else {
                                $field_index[$k] = $fk;
                            }
                        }
                    }
                    switch ($table) {
                        case '\common\models\ChildInfo':
                            ChildInfo::updateAll(['admin' => 0], 'source =' . $hospitalid);
                            $to_object = "ChildInfo";
                            break;
                        case '\common\models\Examination':
                            $to_object = "Examination";
                            break;
                        case '\common\models\Pregnancy':
                            $to_object = "Pregnancy";
                            break;
                        case '\common\models\PublicHealth':
                            $to_object = "PublicHealth";
                            break;
                    }
                    $ossClient->copyObject($bucket, $object, $bucket, $hospitalid . "list/" . $to_object . date('Ymd') . "xlsx");
                    $log->addLog("另存文件");
                    $log->saveLog();
                } else {
                    $ossClient->deleteObject($bucket, $object);
                    $dur->state = 4;
                    $dur->save();
                    return self::DELETE;
                }
            }
        }
        $obj->disconnectWorksheets();
        $log->saveLog();

        $dur->state = 3;
        $dur->save();
        $ossClient->deleteObject($bucket, $object);
        $log->addLog("删除源文件");
        $log->addLog("导入完成");

        $log->saveLog();

        return self::DELETE;
    }

    public static function type($rs, DataUpdateRecord $dur)
    {
        $field_Examination = Examination::$field;
        if (!array_diff($field_Examination, $rs) || array_search('体检日期', $rs)) {
            $dur->type = 1;
            $dur->save();
            return "\common\models\Examination";
        }
        $field_Pregnancy = \common\models\Pregnancy::$field;
        if (!array_diff($field_Pregnancy, $rs) || array_search('产妇姓名', $rs)) {
            $dur->type = 2;
            $dur->save();
            return "\common\models\Pregnancy";
        }
        $field_ChildInfo = ChildInfo::$field;
        if (!array_diff($field_ChildInfo, $rs) || (array_search('母亲姓名', $rs) && array_search('母亲身份证号', $rs))) {
            $dur->type = 3;
            $dur->save();
            return "\common\models\ChildInfo";
        }
        $field_ChildInfo = ChildInfo::$field;
        if ((array_search('所属社区站', $rs) && array_search('本年度是否收取家医服务费', $rs))) {
            $dur->type = 4;
            $dur->save();
            return "\common\models\PublicHealth";
        }
        return false;
    }
}