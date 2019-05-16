<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/5/21
 * Time: 下午4:48
 */

namespace console\controllers;


use common\helpers\WechatSendTmp;
use common\models\ChildInfo;
use common\models\Examination;
use common\models\Log;
use common\models\Notice;
use common\models\UserLogin;
use udokmeci\yii2beanstalk\BeanstalkController;

class ExaUpdateController extends BeanstalkController
{
    public function listenTubes()
    {
        return ['exaupdate'];
    }

    /**
     * 采集数据
     * @param $job Job
     * @return string
     */
    public function actionExaupdate($job='')
    {
        $log=new \common\components\Log("exa_update");

        /** @var object $sentData */
        $sentData = $job->getData();
        $childid=$sentData->childid;
        $log->addLog($childid);
        $child=ChildInfo::findOne(['id'=>$childid]);
        $login = UserLogin::find()->where(['!=','openid',''])->andWhere(['userid'=>$child->userid])->one();
        if($child && !$login){
            $log->addLog('没有登录信息');
            $log->saveLog();
            return self::DELETE;
        }
        $log->addLog($login->openid);

        $exa=Examination::find()->where(['childid'=>$childid])->orderBy('id desc')->one();
        if($exa && $exa->isupdate==1){
            $exa->isupdate=0;
            $exa->save();
            $row=$exa->toArray();
            if ($login->openid) {
                $data = [
                    'first' => array('value' => "您好，宝宝近期的体检结果已更新\n",),
                    'keyword1' => ARRAY('value' => $child->name),
                    'keyword2' => ARRAY('value' => "身高:{$row['field40']},体重:{$row['field70']},头围:{$row['field80']}"),
                    'keyword3' => ARRAY('value' => $row['field9']),
                    'keyword4' => ARRAY('value' => $row['field4']),
                    'remark' => ARRAY('value' => "\n 点击可查看本体检报告的详细内容信息", 'color' => '#221d95'),
                ];
                $miniprogram = [
                    "appid" => \Yii::$app->params['wxXAppId'],
                    "pagepath" => "/pages/user/examination/index?id=" . $child->id,
                ];
                $rs=WechatSendTmp::send($data, $login->openid, \Yii::$app->params['tijian'], '', $miniprogram);

                $log->addLog($rs?'发送成功':'发送失败');
                //小程序首页通知
                Notice::setList($login->userid, 1, ['title' => "宝宝近期的体检结果已更新", 'ftitle' => "点击可查看本体检报告的详细内容信息", 'id' => "/user/examination/index?id=" . $child->id,], "id=" . $child->id);
                $log->saveLog();
            }
        }elseif($exa->isupdate==0){
            $log->addLog('已通知过');
            $log->saveLog();
        }
        return self::DELETE;
    }

    // ...

}