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


    /**
     * 采集数据
     * @param $job Job
     * @return string
     */
    public function actionPush($job='')
    {

        /** @var object $sentData */
        $sentData = $job->getData();
        $artid=$sentData->artid;
        $userids=$sentData->userids;
        $article=\common\models\Article::findOne($artid);
        $data = [
            'first' => array('value' => $article->info->title."\n",),
            'keyword1' => ARRAY('value' => date('Y年m月d H:i'),),
            'keyword2' => ARRAY('value' =>'儿宝宝'),
            'keyword3' => ARRAY('value' =>'儿宝宝'),
            'keyword4' => ARRAY('value' =>'宝爸宝妈'),
            'keyword5' => ARRAY('value' =>$article->info->title),

            'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
        ];
        $miniprogram=[
            "appid"=>\Yii::$app->params['wxXAppId'],
            "pagepath"=>"/pages/article/view/index?id=".$artid,
        ];

        if($article)
        {
            foreach($userids as $k=>$v) {

                $userLogin=UserLogin::findOne(['userid'=>$v]);
                if($userLogin->openid) {
                    $rs=WechatSendTmp::send($data, $userLogin->openid, \Yii::$app->params['zhidao'],'',$miniprogram);
                }
                if($article->art_type!=2)
                {
                    $key=$article->catid==6?3:5;
                    Notice::setList($v, $key, ['title' => $article->info->title, 'ftitle' => date('Y年m月d H:i'), 'id' => "/article/view/index?id=".$artid,]);
                }
            }
        }
        return self::DELETE;
    }

    // ...

}