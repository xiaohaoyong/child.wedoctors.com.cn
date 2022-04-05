<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/11/22
 * Time: 下午3:24
 */

namespace console\controllers;


use common\components\HttpRequest;
use common\components\Log;
use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\AppointOrder1;
use common\models\ArticlePushVaccine;
use common\models\ChildInfo;
use common\models\Hospital;
use common\models\HospitalForm;
use common\models\Notice;
use common\models\UserDoctor;
use common\models\UserLogin;
use yii\base\Controller;

class AppointController extends Controller
{
    public function actionOverdue()
    {

        $day = strtotime(date('Y-m-d'));
        Appoint::updateAll(['state' => 4], 'state=1 and appoint_date <=' . $day);

    }

    public function actionNotice()
    {
        $day = strtotime('+1 day', strtotime(date('Y-m-d 00:00:00')));
        $appoint = Appoint::find()->andWhere(['id'=>1575106])->andWhere(['!=', 'state', 3])->all();

        if ($appoint) {
            foreach ($appoint as $k => $v) {

                $openid = UserLogin::getOpenid($v->userid);
                $doctor=UserDoctor::findOne(['userid'=>$v->doctorid]);
                $hospital=Hospital::findOne($doctor->hospitalid);

                if ($openid) {
                    $temp = '425dIznjAzVkXGMf68801IXJKpgDlO4AKpcEiBkJpZQ';

                    if($v->doctorid==206260){
                        $data = [
                            'first' => array('value' => '您好，你预约了' . date('Y年m月d', $day) . ' 的 ' .Appoint::$typeText[$v->type]."，建议您在". Appoint::$timeText2[$v->appoint_time]  . '到达社区医院！',),
                            'keyword1' => ARRAY('value' => $v->name(),),
                            'keyword2' => ARRAY('value' => $hospital->name,),
                            'keyword3' => ARRAY('value' => '现场确认',),
                            'keyword4' => ARRAY('value' => date('Y年m月d', $day) . ' ' . Appoint::$timeText[$v->appoint_time]),
                            'keyword5' => ARRAY('value' => date('Y年m月d', $day) . ' ' . Appoint::$timeText2[$v->appoint_time]),
                            'remark' => ARRAY('value' => "点击此处填写流行病学调查表，请酌情填写流行病学调查表，根据不同社区工作安排可能需要您出示调查结果，(每位需要去社区的家长请个填一份，请家长互相转发此链接)注：调查表填写后24小时内有效", 'color' => '#221d95'),
                        ];

                        $rs = WechatSendTmp::send($data, $openid, $temp, 'https://cpp.corelines.cn/questionnaire/xcsqwsfwzx/?c=d5b680ceb8b44f209bdef3c84cb15624&qs=one');
                    }elseif (in_array($v->doctorid, [192821, 257888, 184793, 160226,206262,213581])) {
                        $data = [
                            'first' => array('value' => '您好，你预约了' . date('Y年m月d', $day) . ' 的 ' .Appoint::$typeText[$v->type]."，建议您在". Appoint::$timeText2[$v->appoint_time]  . '到达社区医院！',),
                            'keyword1' => ARRAY('value' => $v->name(),),
                            'keyword2' => ARRAY('value' => $hospital->name,),
                            'keyword3' => ARRAY('value' => '现场确认',),
                            'keyword4' => ARRAY('value' => date('Y年m月d', $day) . ' ' . Appoint::$timeText[$v->appoint_time]),
                            'keyword5' => ARRAY('value' => date('Y年m月d', $day) . ' ' . Appoint::$timeText2[$v->appoint_time]),
                            'remark' => ARRAY('value' => "点击此处填写流行病学调查表，请酌情填写流行病学调查表，根据不同社区工作安排可能需要您出示调查结果，(每位需要去社区的家长请个填一份，请家长互相转发此链接)调查结果可以在公众号底部菜单我的->流行病学调查表中查看", 'color' => '#221d95'),
                        ];

                        $rs = WechatSendTmp::send($data, $openid, $temp, 'http://web.child.wedoctors.com.cn/question-naire/form?id=1&doctorid=' . $v->doctorid);
                    } else {
                        $data = [
                            'first' => array('value' => '您好，你预约了' . date('Y年m月d', $day) . ' 的 ' .Appoint::$typeText[$v->type]."，建议您在". Appoint::$timeText2[$v->appoint_time]  . '到达社区医院！',),
                            'keyword1' => ARRAY('value' => $v->name(),),
                            'keyword2' => ARRAY('value' => $hospital->name,),
                            'keyword3' => ARRAY('value' => '现场确认',),
                            'keyword4' => ARRAY('value' => date('Y年m月d', $day) . ' ' . Appoint::$timeText[$v->appoint_time]),
                            'keyword5' => ARRAY('value' => date('Y年m月d', $day) . ' ' . Appoint::$timeText2[$v->appoint_time]),
                            'remark' => ARRAY('value' => "", 'color' => '#221d95'),
                        ];
                        if($v->type!=4) {
                            $data['remark'] = '此消息为系统自动推送，如已取消请忽略。';
                        }
                        if($v->doctorid == 4154){
                            $data['remark'] = '此消息为系统自动推送，如已取消请忽略。如不能赴约请及时取消！';
                        }
                        $rs = WechatSendTmp::send($data, $openid, $temp, '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/appoint/view?id=' . $v->id,]);
                    }
                }

                //推送脊灰疫苗推广文
                if($v->type==2){
                    $child=ChildInfo::findOne(['id'=>$v->childid]);
                    if($child && $child->birthday>strtotime('-74 day') && $child->birthday<strtotime('-30 day')){
                        $aid=1985;
                        $first='就诊当日注意事项和2月龄脊灰疫苗方案早知道，请仔细阅读';


                        if($child->birthday>strtotime('-74 day') && $child->birthday<strtotime('-60 day')) {
                            $aid = 1985;
                            $title='二至三月龄宝宝家长';
                            $footer="";

                        }elseif($child->birthday>strtotime('-44 day') && $child->birthday<strtotime('-30 day')) {
                            $aid = 1985;
                            $title = '一至二月龄宝宝家长';
                            $footer="";

                        }

                        if(!$title) continue;
                        $article=\common\models\ArticleInfo::findOne($aid);
                        $data = [
                            'first' => array('value' => $first),
                            'keyword1' => ARRAY('value' => date('Y年m月d H:i')),
                            'keyword2' => ARRAY('value' => '儿宝宝'),
                            'keyword3' => ARRAY('value' => '儿宝宝'),
                            'keyword4' => ARRAY('value' => $title),
                            'keyword5' => ARRAY('value' => $article->title),
                            'remark' => ARRAY('value' => "为了您宝宝健康，请仔细阅读哦。", 'color' => '#221d95'),];
                        $url = \Yii::$app->params['site_url'] . "#/mission-read";
                        $miniprogram = [
                            "appid" => \Yii::$app->params['wxXAppId'],
                            "pagepath" => "pages/article/view/index?id=$aid",
                        ];

                        Notice::setList($v->userid, 3, ['title' =>  $article->title, 'ftitle' => $title, 'id' => "/article/view/index?id=$aid"]);
                        $pushReturn = \common\helpers\WechatSendTmp::send($data, $openid, \Yii::$app->params['zhidao'], $url, $miniprogram);
                        var_dump($pushReturn);exit;
//                        $articlePushVaccine=ArticlePushVaccine::findOne(['openid'=>$openid,'aid'=>$aid]);
//                        if(!$articlePushVaccine || $articlePushVaccine->state!=1) {
//                            $pushReturn = \common\helpers\WechatSendTmp::send($data, $openid, \Yii::$app->params['zhidao'], $url, $miniprogram);
//                            $articlePushVaccine = new ArticlePushVaccine();
//                            $articlePushVaccine->aid = $aid;
//                            $articlePushVaccine->openid = $openid;
//                            $articlePushVaccine->state = $pushReturn?1:0;
//                            $articlePushVaccine->save();
//                        }

                    }
                }
            }
        }
    }

    //国医数据导入
    public function actionGPush()
    {
        $date = date('Y-m-d');

        // echo strtotime($date);exit;
        $path = "https://rainbow.arxanfintech.com/book/rainbow/v1/query_hospital_reserve?reserve_datetime={$date}&access_token=0e35fa1c06da4b2e89e2f21860093bd7";
        $curl = new HttpRequest($path, true, 10);
        $userJson = $curl->get();
        $list = json_decode($userJson, true);

        $time = ['08' => 1, '09' => 2, '10' => 3, '13' => 4, '14' => 5, '15' => 6, '16' => 6];
        foreach ($list['retbody'] as $k => $v) {
            $appoint = [];

            $rs = $v;
            $birthday = strtotime($rs['baby_birthday']);
            $appointOrder = AppointOrder1::findOne(['birthday' => $birthday, 'name' => $rs['baby_name']]);
            if (!$appointOrder) {
                $appointOrder = new AppointOrder1();
                $appointOrder->birthday = $birthday;
                $appointOrder->name = $rs['baby_name'];
                $appointOrder->save();
                var_dump($appointOrder->firstErrors);
            }
            $orderid = $appointOrder->id;

            $ti = explode(':', $rs['reserve_period']);
            $app = Appoint::find()->where(['appoint_time' => $time[$ti[0]], 'appoint_date' => strtotime($rs['reserve_datetime']), 'userid' => 0, 'doctorid' => 216593, 'type' => 2, 'orderid' => $orderid])->one();
            $app = $app ? $app : new Appoint();
            $appoint['appoint_time'] = $time[$ti[0]];
            $appoint['appoint_date'] = strtotime($rs['reserve_datetime']);
            $appoint['userid'] = 0;
            $appoint['doctorid'] = 216593;
            $appoint['type'] = 2;
            $appoint['childid'] = 0;
            $appoint['phone'] = 0;
            $appoint['state'] = 2;
            $appoint['login'] = 0;
            $appoint['mode'] = 2;
            $appoint['vaccine'] = 0;
            $appoint['month'] = 0;
            $appoint['orderid'] = $orderid;
            $app->load(['Appoint' => $appoint]);
            $app->save();
            var_dump($app->firstErrors);
        }


        $satime = strtotime('-30 day', strtotime(date('Y-m-d')));
        $doctors = UserDoctor::find()->where(['userid' => 216593])->all();
        foreach ($doctors as $k => $v) {
            echo $v->name;
            for ($stime = $satime; $stime < time(); $stime = strtotime('+1 day', $stime)) {
                echo date('Ymd', $stime);
                $etime = strtotime('+1 day', $stime);

                $appoint = Appoint::find()->where(['doctorid' => $v->userid])
                    ->andWhere(['appoint_date' => $stime])
                    ->andWhere(['!=', 'state', 3])
                    ->count();
                $rs = [];

                $rs['appoint_num'] = $appoint;
                $rs['doctorid'] = $v->userid;
                $rs['date'] = $stime;
                $hospitalFrom = HospitalForm::find()->where(['doctorid' => $v->userid])->andWhere(['date' => $stime])->one();
                $hospitalFrom = $hospitalFrom ? $hospitalFrom : new HospitalForm();
                $hospitalFrom->load(['HospitalForm' => $rs]);
                $hospitalFrom->save();
                echo "\n";
            }
        }
    }
}