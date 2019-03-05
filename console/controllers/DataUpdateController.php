<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/5/21
 * Time: 下午4:48
 */

namespace console\controllers;

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
        $log->saveLog();


        return self::DELETE;
    }
}