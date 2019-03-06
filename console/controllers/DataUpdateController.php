<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/5/21
 * Time: 下午4:48
 */

namespace console\controllers;

use OSS\Core\OssException;
use OSS\OssClient;
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
        $localfile = "/tmp";
        $options = array(
            OssClient::OSS_FILE_DOWNLOAD => $localfile
        );

        try{
            $ossClient = new OssClient('LTAIteFpOZnX3aoE', 'lYWI5AzSjQiZWBhC2d7Ttt06bnoDFF', 'oss-cn-beijing.aliyuncs.com');

            $ossClient->getObject($bucket, $object, $options);
        } catch(OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getErrorCode() . "\n");

            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK, please check localfile: 'upload-test-object-name.txt'" . "\n");



        $log->saveLog();


        return self::DELETE;
    }
}