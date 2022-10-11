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
use common\models\HospitalAppoint;
use common\models\HospitalAppointVaccine;
use common\models\HospitalAppointVaccineNum;
use common\models\HospitalAppointWeek;
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
        $appoint = Appoint::find()->where(['appoint_date' => $day])->andWhere(['not in', 'doctorid', [221895]])->andWhere(['!=', 'state', 3])->all();

        if ($appoint) {
            foreach ($appoint as $k => $v) {

                $openid = UserLogin::getOpenid($v->userid);
                $doctor=UserDoctor::findOne(['userid'=>$v->doctorid]);
                $hospital=Hospital::findOne($doctor->hospitalid);

                if ($openid) {
                    $temp = '425dIznjAzVkXGMf68801IXJKpgDlO4AKpcEiBkJpZQ';
                    if($v->doctorid==4146){
                        continue;
                    }
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

                        $rs = WechatSendTmp::send($data, $openid, $temp, 'https://cpp.corelines.cn/questionnaire/xcsqwsfwzx/?c=d5b680ceb8b44f209bdef3c84cb15624&qs=one',[],$v->id);
                    }elseif (in_array($v->doctorid, [192821, 257888, 184793, 160226,206262])) {
                        $data = [
                            'first' => array('value' => '您好，你预约了' . date('Y年m月d', $day) . ' 的 ' .Appoint::$typeText[$v->type]."，建议您在". Appoint::$timeText2[$v->appoint_time]  . '到达社区医院！',),
                            'keyword1' => ARRAY('value' => $v->name(),),
                            'keyword2' => ARRAY('value' => $hospital->name,),
                            'keyword3' => ARRAY('value' => '现场确认',),
                            'keyword4' => ARRAY('value' => date('Y年m月d', $day) . ' ' . Appoint::$timeText[$v->appoint_time]),
                            'keyword5' => ARRAY('value' => date('Y年m月d', $day) . ' ' . Appoint::$timeText2[$v->appoint_time]),
                            'remark' => ARRAY('value' => "点击此处填写流行病学调查表，请酌情填写流行病学调查表，根据不同社区工作安排可能需要您出示调查结果，(每位需要去社区的家长请个填一份，请家长互相转发此链接)调查结果可以在公众号底部菜单我的->流行病学调查表中查看", 'color' => '#221d95'),
                        ];

                        $rs = WechatSendTmp::send($data, $openid, $temp, 'http://web.child.wedoctors.com.cn/question-naire/form?id=1&doctorid=' . $v->doctorid,[],$v->id);
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
                        $data['remark'] = "请点击查看预约二维码。";
                        if($v->type!=4) {
                            $data['remark'] = '此消息为系统自动推送，如已取消请忽略。';
                        }
                        if($v->doctorid == 4154){
                            $data['remark'] = '此消息为系统自动推送，如已取消请忽略。如不能赴约请及时取消！';
                        }
                        $rs = WechatSendTmp::send($data, $openid, $temp, '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/appoint/view?id=' . $v->id,],$v->id);
                    }
                }

                //推送脊灰疫苗推广文
                if($v->type==2){
                    $child=ChildInfo::findOne(['id'=>$v->childid]);
                    if($child && $child->birthday>strtotime('-60 day')){
                        $aid=1985;
                        $first='就诊当日注意事项和2月龄脊灰疫苗方案早知道，请仔细阅读';
                        $title = '宝宝家长';


                        if(!$title) continue;
                        $article=\common\models\ArticleInfo::findOne($aid);
                        $data = [
                            'first' => array('value' => $first),
                            'keyword1' => ARRAY('value' => date('Y年m月d H:i')),
                            'keyword2' => ARRAY('value' => '儿宝宝'),
                            'keyword3' => ARRAY('value' => '儿宝宝'),
                            'keyword4' => ARRAY('value' => $title),
                            'keyword5' => ARRAY('value' => $article->title),
                            'remark' => ARRAY('value' => "为了您宝宝健康，请点击查看。", 'color' => '#221d95'),];
                        $url = \Yii::$app->params['site_url'] . "#/mission-read";
                        $miniprogram = [
                            "appid" => \Yii::$app->params['wxXAppId'],
                            "pagepath" => "pages/article/view/index?id=$aid",
                        ];

                        Notice::setList($v->userid, 3, ['title' =>  $article->title, 'ftitle' => $title, 'id' => "/article/view/index?id=$aid"]);
                        $pushReturn = \common\helpers\WechatSendTmp::send($data, $openid, \Yii::$app->params['zhidao'], $url, $miniprogram,$aid);
                        $articlePushVaccine = new ArticlePushVaccine();
                        $articlePushVaccine->aid = $aid;
                        $articlePushVaccine->openid = $openid;
                        $articlePushVaccine->state = $pushReturn?1:0;
                        $articlePushVaccine->save();

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

    public function actionState(){
        $time = time();
        $appoints = Appoint::find()->where(['state' => 6])->orderBy('id asc')->all();
        foreach ($appoints as $k=>$v){
            $log=new \common\components\Log('appoint-state',true);
            $log->addLog($time);

            $log->addLog($v->doctorid);

            $log->addLog($v->id);

            $week = date('w', $v->appoint_date);
            //0判断是否已经存在预约
            $appoint = HospitalAppoint::findOne(['doctorid' => $v->doctorid, 'type' => $v->type]);
            if(!in_array($v->vaccine,[64,66,46])) {
                $app = Appoint::find()->where(['state' => 1])->andWhere(['type' => $v->type])->andWhere(['phone' => $v->phone])->one();
                if ($app) {
                    $v->state = 3;
                    $v->cancel_type = 5;
                    $v->save();
                    $log->addLog($v->state);
                    $log->addLog("已存在进行中");
                    $log->saveLog();
                    continue;
                }
            }
            $log->addLog('疫苗:'.$v->vaccine);
            //1判断疫苗是否约满
            if($v->vaccine){
                $query = Appoint::find()
                    ->andWhere(['type' => $v->type])
                    ->andWhere(['doctorid' => $v->doctorid])
                    ->andWhere(['appoint_date' =>$v->appoint_date])
                    ->andWhere(['mode' => 0])
                    ->andWhere(['<','state',3]);
                $query->andWhere(['vaccine'=>$v->vaccine]);
                $appoint_count=$query->count();

                $hospitalAppointVaccine=HospitalAppointVaccine::findOne(['haid' => $appoint->id, 'week' => $week, 'vaccine' => $v->vaccine]);
                if(!$hospitalAppointVaccine){
                    $v->state = 3;
                    $v->cancel_type = 5;
                    $v->save();
                    $log->addLog("号源已取消");
                    $this->pushTmp($v);
                    continue;
                }
                $hospitalAppointVaccineNum = HospitalAppointVaccineNum::findOne(['haid' => $appoint->id, 'week' => $week, 'vaccine' => $v->vaccine]);
                $log->addLog("设置数：" . $hospitalAppointVaccineNum->num);
                $log->addLog("已约数：" . $appoint_count);
                if ($hospitalAppointVaccineNum && ($hospitalAppointVaccineNum->num - $appoint_count <= 0)) {
                    $v->state = 3;
                    $v->cancel_type = 5;
                    $v->save();
                    $log->addLog($v->state);
                    $log->saveLog();
                    $this->pushTmp($v);
                    continue;
                }
            }
            //2判断预约时间段是否约满
            $log->addLog('预约时间段:'.$v->appoint_time);

            $query = Appoint::find()
                ->andWhere(['type' => $v->type])
                ->andWhere(['doctorid' => $v->doctorid])
                ->andWhere(['appoint_date' =>$v->appoint_date])
                ->andWhere(['appoint_time' => $v->appoint_time])
                ->andWhere(['mode' => 0])
                ->andWhere(['<','state',3]);
            $appoint_count = $query->count();
            $weeks = HospitalAppointWeek::find()
                ->andWhere(['week' => $week])
                ->andWhere(['haid' => $appoint->id])
                ->andWhere(['time_type' => $v->appoint_time])->one();
            $log->addLog("1设置数：".$weeks->num);
            $log->addLog("1已约数：".$appoint_count);
            if (($weeks->num - $appoint_count) <= 0) {
                $v->state=3;
                $v->cancel_type=5;
                $v->save();
                $log->addLog($v->state);

                $log->saveLog();
                $this->pushTmp($v);
                continue;
            }

            $v->state=1;
            $v->save();
            $log->addLog($v->state);
            $log->saveLog();
            $this->pushTmp($v);
        }
    }
    public function pushTmp(Appoint $appoint){
        if($appoint->state == 1) {
            $data = [
                'first' => ['value' => ''],
                'keyword1' => array('value' => Appoint::$typeText[$appoint->type]),
                'keyword2' => array('value' => $appoint->name()),
                'keyword3' => array('value' => $appoint->phone),
                'keyword4' => array('value' => date('Y-m-d',$appoint->appoint_date)),
                'keyword5' => array('value' => Appoint::$timeText[$appoint->appoint_time]),
                'remark' => array('value' => "尊敬的用户您好，您的预约已生效，请您按照预约时间前往社区，如有问题请联系在线客服"),
            ];
            $tmpid = '83CpoxWB9JCnwdXPr0H7dB66QQnFdJQvBbeMnJ9rdHo';
        }else{
            $data = [
                'first' => ['value' => ''],
                'keyword1' => array('value' => $appoint->name()),
                'keyword2' => array('value' => $appoint->phone),
                'keyword3' => array('value' => date('Y-m-d',$appoint->appoint_date)),
                'keyword4' => array('value' => Appoint::$typeText[$appoint->type]),
                'keyword5' => array('value' => '预约人数较多未能成功预约'),
                'remark' => array('value' => "尊敬的用户您好，很抱歉的通知您，由于预约量较大，您未能预约成功已自动取消，如有问题请联系在线客服"),
            ];
            $tmpid = 'Osp0A-3RxrPZpyts8GhASy8jOJLGC6y0m_DHlaF-Z3c';
        }
        $login = UserLogin::findOne(['id' => $appoint->loginid]);
        return $rs = WechatSendTmp::send($data, $login->openid, $tmpid,'',[],0,0);
    }
}