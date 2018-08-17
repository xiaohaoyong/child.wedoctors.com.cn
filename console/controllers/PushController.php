<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/8/15
 * Time: 下午3:43
 */

namespace console\controllers;
use common\components\Log;
use common\helpers\WechatSendTmp;
use common\models\Article;
use common\models\ArticleSend;
use common\models\ChildInfo;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\WeOpenid;
use yii\base\Controller;


class PushController extends Controller
{
    /**
     * 未完成注册用户
     * 人群：当天扫码，未授权，未添加儿童
     * 时间：每晚8点
     */
    public function actionRegisterUnfinished(){
        $doctorids=[];
        $openids=[];
        $time=strtotime(date('Y-m-d 20:00:00',strtotime('-1 day')));
        $weopenid=WeOpenid::find()->andFilterWhere(['>','createtime',$time])->andWhere(['level'=>0])->all();
        foreach ($weopenid as $k => $v) {
            $is_push=0;
            if(!$openids[$v->openid]) {
                $doctor = $doctorids[$v->doctorid];
                if (!$doctor) {
                    $doctor = UserDoctor::findOne(['userid' => $v->doctorid]);
                    $doctorids[$v->doctorid] = $doctor;
                }
                if (!$v->level) {
                    $is_push = 1;
                } else {
                    $userLogin=UserLogin::findOne(['xopenid'=>$v->xopenid]);
                    if($userLogin){
                        $child=ChildInfo::findOne(['userid'=>$userLogin->userid]);
                        if(!$child){
                            $is_push = 1;
                        }
                    }
                }
                if($is_push==1) {
                    $data = [
                        'first' => array('value' => "您好，为确保享受儿童中医药健康指导服务,请完善宝宝信息\n",),
                        'keyword1' => ARRAY('value' => "宝宝基本信息"),
                        'keyword2' => ARRAY('value' => $doctor->name),
                        'remark' => ARRAY('value' => "\n 点击授权并完善宝宝信息，如果已添加宝宝请忽略此条提醒", 'color' => '#221d95'),
                    ];
                    //var_dump($doctor->name);
                    $rs = WechatSendTmp::send($data, $v->openid, 'wiVMfEAlt4wYwfpjcawOTDwgUN8SRPIH1Fc8wVWfGEI', '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/index/index',]);
                    $log=new Log('RegisterUnfinished');
                    $log->addLog($v->openid);
                    $log->addLog($rs['errcode'].$rs['errmsg']);
                    $log->saveLog();
                    $openids[$v->openid] = 1;
                    usleep(300000);
                }
            }
        }
        exit;
    }

    public function actionArticleSend(){
        $articleids=[];
        $child_types=Article::$childText;
        foreach($child_types as $k=>$v){
            $article=\common\models\Article::find()
                ->select('article_info.title')->indexBy('id')
                ->leftJoin('article_info', '`article_info`.`id` = `article`.`id`')
                ->where(['article.child_type'=>$v,'article.type'=>0])->column();
            $articleids[$k][]=$article;
        }

        $redis=\Yii::$app->rdmp;
        $hospital=$redis->hgetall('article_send_ispush');
        foreach($hospital as $k=>$v){
            if($k%2==0){
                $key=$v;
            }else {
                $doctorid = UserDoctor::findOne(['hospitalid' => $key])->userid;
                if($v==1){
                    foreach($articleids as $ak=>$av) {
                        $articleSend = new ArticleSend();
                        //$articleSend->artid=$av;
                        $articleSend->type = $ak;
                        $articleSend->doctorid = $doctorid;
                        $articleSend->send('automatic',true);
                    }
                }
            }
        }
    }
}