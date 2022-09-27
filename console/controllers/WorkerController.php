<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/5/21
 * Time: 下午4:48
 */

namespace console\controllers;


use common\helpers\WechatSendTmp;
use common\models\Log;
use common\models\Notice;
use common\models\UserLogin;
use udokmeci\yii2beanstalk\BeanstalkController;

class WorkerController extends BeanstalkController
{
    public function listenTubes()
    {
        return ['push'];
    }

    public $lineLog;


    /**
     * 采集数据
     * @param $job Job
     * @return string
     */
    public function actionPush($job='')
    {
        $this->lineLog="";
        /** @var object $sentData */
        $sentData = $job->getData();
        $artid=$sentData->artid;
        $userids=$sentData->userids;
        $log=new \common\components\Log("pushWorker");
        $log->addLog($artid);
        $log->addLog(implode("||",$userids));

        $article=\common\models\Article::findOne($artid);
        if($article)
        {

            if($article->type==2){

                $data = [
                    'first' => array('value' => $article->info->title."\n",),
                    'keyword1' => ARRAY('value' => "儿宝宝用户"),
                    'keyword2' => ARRAY('value' => date('Y年m月d H:i')),
                    'keyword3' => ARRAY('value' => strip_tags($article->info->ftitle)),

                    'remark' => ARRAY('value' => "\n 点击查看社区官方通知详情", 'color' => '#221d95'),
                ];

                $temp='VXAAPM2bzk1zGHAOnj8cforjriNp3wsg4ZewGEUck_0';
            }else{

                $data = [
                    'first' => array('value' => $article->info->title."\n",),
                    'keyword1' => ARRAY('value' => date('Y年m月d H:i'),),
                    'keyword2' => ARRAY('value' =>'儿宝宝'),
                    'keyword3' => ARRAY('value' =>'儿宝宝'),
                    'keyword4' => ARRAY('value' =>'宝爸宝妈'),
                    'keyword5' => ARRAY('value' =>$article->info->title),

                    'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
                ];

                $temp= \Yii::$app->params['zhidao'];
            }

            $miniprogram=[
                "appid"=>\Yii::$app->params['wxXAppId'],
                "pagepath"=>"/pages/article/view/index?id=".$artid,
            ];
            $log->saveLog();


            foreach($userids as $k=>$v) {
//                $userLogin=UserLogin::findAll(['userid'=>$v]);
//                if($userLogin) {
//                    foreach ($userLogin as $ulk => $ulv) {
//                        if ($ulv->openid  && $article->type==2) {
//                            //$rs = WechatSendTmp::send($data, $ulv->openid, $temp, '', $miniprogram);
//                            $log->addLog($ulv->openid);
//                            $log->addLog($rs?'true':'false');
//                        }
//                    }
//                }
                if($article->art_type!=2)
                {
                    $key=$article->catid==6?3:5;
                    Notice::setList($v, $key, ['title' => $article->info->title, 'ftitle' => date('Y年m月d H:i'), 'id' => "/article/view/index?id=".$artid,]);
                }
            }
            $log->saveLog();
        }
        return self::DELETE;
    }
}