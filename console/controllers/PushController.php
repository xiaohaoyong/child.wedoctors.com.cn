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
use common\models\ArticleInfo;
use common\models\ArticleSend;
use common\models\ArticleType;
use common\models\ArticleUser;
use common\models\ChildInfo;
use common\models\Notice;
use common\models\Pregnancy;
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
    public function actionRegisterUnfinished()
    {
        $doctorids = [];
        $openids = [];
        $time = strtotime(date('Y-m-d 20:00:00', strtotime('-1 day')));
        $redis = \Yii::$app->rdmp;

        $weopenid = WeOpenid::find()->andFilterWhere(['>', 'createtime', $time])->andWhere(['level' => 0])->all();
        foreach ($weopenid as $k => $v) {
            if (!$openids[$v->openid]) {
                $doctor = $doctorids[$v->doctorid];
                if (!$doctor) {
                    $doctor = UserDoctor::findOne(['userid' => $v->doctorid]);
                    $doctorids[$v->doctorid] = $doctor;
                    $redis->ZREM("RegisterUnfinished" . $doctor->hospitalid . date('Ymd'), "total1");
                    $redis->ZREM("RegisterUnfinished" . $doctor->hospitalid . date('Ymd'), "total1ok");
                    $redis->ZREM("RegisterUnfinished" . $doctor->hospitalid . date('Ymd'), "total2");
                    $redis->ZREM("RegisterUnfinished" . $doctor->hospitalid . date('Ymd'), "total2ok");

                }

                $redis->ZINCRBY("RegisterUnfinished" . $doctor->hospitalid . date('Ymd'), 1, "total1");

                $data = [
                    'first' => array('value' => "您好，为确保享受儿童中医药健康指导服务,请完善宝宝信息\n",),
                    'keyword1' => ARRAY('value' => "宝宝基本信息"),
                    'keyword2' => ARRAY('value' => $doctor->name),
                    'remark' => ARRAY('value' => "\n 点击授权并完善宝宝信息，如果已添加宝宝请忽略此条提醒", 'color' => '#221d95'),
                ];
                //var_dump($doctor->name);

                $rs = WechatSendTmp::send($data, $v->openid, 'wiVMfEAlt4wYwfpjcawOTDwgUN8SRPIH1Fc8wVWfGEI', '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/index/index',]);
                $log = new Log('RegisterUnfinished');
                $log->addLog($v->openid);
                $log->addLog($rs?"ok":"no");
                $log->saveLog();
                $openids[$v->openid] = 1;

                if ($rs) {
                    $redis->ZINCRBY("RegisterUnfinished" . $doctor->hospitalid . date('Ymd'), 1, "total1ok");
                }

                usleep(300000);
            }
        }


        $list = \common\models\DoctorParent::find()
            ->leftJoin('child_info', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andWhere(['>', 'doctor_parent.createtime', $time])
            ->andWhere(['child_info.userid' => null])
            ->all();
        foreach ($list as $k => $v) {

            $userLogin = UserLogin::find()->andWhere(['userid' => $v->parentid])->andWhere(['!=', 'openid', ''])->one();

            if (!$openids[$userLogin->openid]) {

                $doctor = $doctorids[$v->doctorid];
                if (!$doctor) {
                    $doctor = UserDoctor::findOne(['userid' => $v->doctorid]);
                    $doctorids[$v->doctorid] = $doctor;
                }

                $redis->ZINCRBY("RegisterUnfinished" . $doctor->hospitalid . date('Ymd'), 1, "total2");

                $data = [
                    'first' => array('value' => "您好，为确保享受儿童中医药健康指导服务,请完善宝宝信息\n",),
                    'keyword1' => ARRAY('value' => "宝宝基本信息"),
                    'keyword2' => ARRAY('value' => $doctor->name),
                    'remark' => ARRAY('value' => "\n 点击授权并完善宝宝信息，如果已添加宝宝请忽略此条提醒", 'color' => '#221d95'),
                ];
                //var_dump($doctor->name);
                $rs = WechatSendTmp::send($data, $userLogin->openid, 'wiVMfEAlt4wYwfpjcawOTDwgUN8SRPIH1Fc8wVWfGEI', '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/index/index',]);
                $log = new Log('RegisterUnfinished');
                $log->addLog($userLogin->openid);
                $log->addLog($rs?"ok":"no");
                $log->saveLog();
                $openids[$userLogin->openid] = 1;

                if ($rs) {
                    $redis->ZINCRBY("RegisterUnfinished" . $doctor->hospitalid . date('Ymd'), 1, "total2ok");
                }
                usleep(300000);
            }
        }
    }

    public function actionArticleSend()
    {
        $articleids = [];
//        push

        $hospitals = UserDoctor::find()->select('hospitalid')->andWhere(['not in','userid',[184793,176156]])->andWhere(['is_guanfang'=>0])->column();
        foreach($hospitals as $hk=>$hv) {
            $doctor = UserDoctor::findOne(['hospitalid' => $hv]);
            $childs=ChildInfo::find()
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctor->userid])
                //->andWhere(['>','birthday',strtotime("-37 month")])
                ->all();
            foreach ($childs as $ck=>$cv){
                $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $cv->birthday));
                $month= $DiffDate[0]*12+$DiffDate[1];
                $key=0;
                $child_type=0;
                foreach (Article::$childMonth as $ak=>$av)
                {
                    if($av>$month){
                        break;
                    }
                    $articleUser = ArticleUser::find()->where(['childid' => $cv->id, 'child_type' => $ak])->one();
                    if(!$articleUser) {
                        $article = \common\models\Article::find()
                            ->where(['type' => 1])
                            ->andWhere(['child_type' => $ak])
                            ->all();
                        foreach ($article as $ark => $arv) {
                            $au = ArticleUser::find()->where(['childid' => $cv->id, 'artid' => $arv->id])->one();
                            if (!$au || date('Ymd',$au->createtime)=='20220408') {
                                $au = $au?$au:new ArticleUser();
                                $au->childid = $cv->id;
                                $au->touserid = $cv->userid;
                                $au->createtime = $cv->birthday+($av*30*86400);
                                $au->userid = $doctor->userid;
                                $au->artid = $arv->id;
                                $au->child_type = $ak;
                                $au->save();
                                var_dump($au->firstErrors);
                            }
                        }
                        if(!$au->firstErrors) {
                            $typename = Article::$childText[$key];
                            $url = \Yii::$app->params['site_url'] . "#/mission-read";
                            $miniprogram = [
                                "appid" => \Yii::$app->params['wxXAppId'],
                                "pagepath" => "pages/article/guidance/index?t=0",
                            ];
                            $data = [
                                'first' => array('value' => "您好！医生给您发来了{$typename}儿童中医药健康指导。\n"),
                                'keyword1' => array('value' => date('Y年m月d H:i')),
                                'keyword2' => array('value' => $doctor->hospital->name),
                                'keyword3' => array('value' => $doctor->name),
                                'keyword4' => array('value' => $cv->name),
                                'keyword5' => array('value' => "{$typename}儿童中医药健康指导"),
                                'remark' => array('value' => "\n为了您宝宝健康，请仔细阅读哦。", 'color' => '#221d95'),
                            ];
                            $touser = UserLogin::find()->where(['userid' => $cv->userid])->andWhere(['!=', 'openid', ''])->one();
                            WechatSendTmp::send($data, $touser->openid, \Yii::$app->params['zhidao'], $url, $miniprogram);
                            //小程序首页推送
                            Notice::setList($cv->userid, 4, [
                                'title' => "{$typename}儿童中医药健康指导。",
                                'ftitle' => $doctor->name . '提醒您及时查看',
                                'id' => '/article/guidance/index?t=0'
                            ]);
                        }
                    }
               }
//                if($child_type){
//                    $articleUser = ArticleUser::find()->where(['childid' => $cv->id, 'child_type' => $key])->one();
//                    if(!$articleUser){
//                        $article = \common\models\Article::find()
//                            ->where(['type' => 1])
//                            ->andWhere(['child_type'=>$key])
//                            ->all();
//                        foreach($article as $ark=>$arv){
//                            $au = ArticleUser::find()->where(['childid' => $cv->id, 'artid' => $arv->id])->one();
//                            if(!$au) {
//                                $au = new ArticleUser();
//                                $au->childid = $cv->id;
//                                $au->touserid = $cv->userid;
//                                $au->createtime = time();
//                                $au->userid = $doctor->userid;
//                                $au->artid = $arv->id;
//                                $au->child_type = $key;
//                                $au->save();
//                                var_dump($au->firstErrors);
//                            }
//                        }
////                        
//                    }
//                }
            }
        }
    }

    public function actionGravidaSend(){
        foreach(ArticleType::$week as $k=>$v){
            $field16 = strtotime(date('Y-m-d')) - ($v * 3600 * 24*7);
            $preg=Pregnancy::find()->select('familyid')->andWhere(['field16'=>$field16])->andWhere(['!=','familyid',0])->column();
            $articles=Article::find()->leftJoin(ArticleType::tableName(),ArticleType::tableName().'.aid='.Article::tableName().'.id')
                ->where([ArticleType::tableName().'.type'=>$k])->andWhere([Article::tableName().".type"=>3])
                ->all();
            if($articles) {
                foreach ($articles as $ak => $av) {
                    $data = [
                        'first' => array('value' => $av->info->title . "\n",),
                        'keyword1' => ARRAY('value' => date('Y年m月d H:i'),),
                        'keyword2' => ARRAY('value' => '儿宝宝'),
                        'keyword3' => ARRAY('value' => '儿宝宝'),
                        'keyword4' => ARRAY('value' => '准妈妈'),
                        'keyword5' => ARRAY('value' => $av->info->title),

                        'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
                    ];
                    $miniprogram=[
                        "appid"=>\Yii::$app->params['wxXAppId'],
                        "pagepath"=>"/pages/article/view/index?id=".$av->id,
                    ];
                    $temp = \Yii::$app->params['zhidao'];
                    foreach($preg as $pk=>$pv) {
                        $openid=UserLogin::getOpenid($pv);
                        if($openid){
                            var_dump($openid);
                            $rs=WechatSendTmp::send($data, $openid,$temp,'',$miniprogram);
                            var_dump($rs);
                            exit;
                        }
                    }
                }
            }
        }
    }
}