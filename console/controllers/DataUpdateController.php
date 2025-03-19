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
    public function actionDataupdate($job='')
    {
        /** @var object $sentData */
        $sentData = $job->getData();
        $hospitalid=$sentData->hospitalid;
        $date=$sentData->date;
        $id= $sentData->id;

        $dur=DataUpdateRecord::findOne($id);
        $dur->state=1;
        $dur->save();

        $log=new \common\components\Log('datacallback',true);
        $log->addLog('异步任务');
        $log->addLog(json_encode([$hospitalid,$date]));

        $bucket= "wedoctorschild";
        try{
            $ossClient = new OssClient(\Yii::$app->params['aliak'], \Yii::$app->params['aliaks'], 'oss-cn-beijing.aliyuncs.com');
            $prefix = $hospitalid.'/';
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
            if($objectList[0]) {
                $object = $objectList[0]->getKey();
                // 指定文件下载路径。
                $localfile = "/tmp/" . $hospitalid . ".xlsx";
                $options = array(
                    OssClient::OSS_FILE_DOWNLOAD => $localfile
                );
                $ossClient->getObject($bucket, $object, $options);
                $log->addLog("下载成功");
                $log->saveLog();
            }else {
                $log->addLog("未找到文件");
                $log->saveLog();
                return self::DELETE;
            }

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
        ini_set('memory_limit','4096M');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($localfile);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $log->addLog("解析成功");

        $dur->state=2;
        $dur->save();

        $data1 = $data;
        $headerRow = array_shift($data);
        $table=self::type($headerRow,$dur);
        if($table) {
            $log->addLog("匹配成功$table");

            if ($table != '\common\models\ChildInfo') {
                $return = $this->mapTableData($table::$field, $data1);
            } else {
                $return = $this->mapTableData($table::$field, $data1, '');
            }
            $log->addLog("开始导入");
            foreach ($return as $k=>$v) {
                $table::inputData($v, $hospitalid);
            }
            $log->addLog("导入成功");

            switch ($table){
                case '\common\models\ChildInfo':
                    ChildInfo::updateAll(['admin'=>0],'source ='.$hospitalid);
                    $to_object="ChildInfo";
                    break;
                case '\common\models\Examination':
                    $to_object="Examination";
                    break;
                case '\common\models\Pregnancy':
                    $to_object="Pregnancy";
                    break;
                case '\common\models\PublicHealth':
                    $to_object="PublicHealth";
                    break;
            }
            $ossClient->copyObject($bucket, $object, $bucket, $hospitalid."list/".$to_object.date('Ymd')."xlsx");

        }else{
            $ossClient->deleteObject($bucket, $object);
            $dur->state=4;
            $dur->save();
            return self::DELETE;
        }

        $dur->state=3;
        $dur->save();
        $ossClient->deleteObject($bucket, $object);
        $log->addLog("删除源文件");
        $log->addLog("导入完成");

        $log->saveLog();
        return self::DELETE;
    }
    public static function type($rs,DataUpdateRecord $dur){
        $field_Examination=Examination::$field;
        if(!array_diff($field_Examination,$rs) || array_search('体检日期',$rs)){
            $dur->type=1;
            $dur->save();
            return "\common\models\Examination";
        }
        $field_Pregnancy=\common\models\Pregnancy::$field;
        if(!array_diff($field_Pregnancy,$rs) || array_search('产妇姓名',$rs)){
            $dur->type=2;
            $dur->save();
            return "\common\models\Pregnancy";
        }
        $field_ChildInfo=ChildInfo::$field;
        if(!array_diff($field_ChildInfo,$rs) || (array_search('母亲姓名',$rs) && array_search('母亲身份证号',$rs))){
            $dur->type=3;
            $dur->save();
            return "\common\models\ChildInfo";
        }
        $field_ChildInfo=ChildInfo::$field;
        if((array_search('所属社区站',$rs) && array_search('本年度是否收取家医服务费',$rs))){
            $dur->type=4;
            $dur->save();
            return "\common\models\PublicHealth";
        }
        return false;
    }
    function mapTableData($field, $data,$prefix='field') {
        // 反转字段数组，中文作为键，英文作为值
        $flippedField = array_flip($field);

        // 提取表头行，剩下的数据行
        $headerRow = array_shift($data);

        // 构建列索引到英文键的映射
        $columnMap = [];
        foreach ($headerRow as $index => $chineseHeader) {
            if (isset($flippedField[$chineseHeader])) {
                $columnMap[$index] = $prefix.$flippedField[$chineseHeader];
            }
            // 忽略未定义的表头列
        }

        // 转换数据行
        $processedData = [];
        foreach ($data as $row) {
            $newRow = [];
            foreach ($row as $index => $value) {
                if (isset($columnMap[$index])) {
                    $newRow[$columnMap[$index]] = $value;
                }
                // 忽略未映射的列
            }
            $processedData[] = $newRow;
        }

        return $processedData;
    }
}