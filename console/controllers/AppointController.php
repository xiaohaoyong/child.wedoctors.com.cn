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
use common\models\Hospital;
use common\models\HospitalForm;
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

                    if (in_array($v->doctorid, [192821, 206260, 257888, 184793, 160226,206262])) {
//                        $data = [
//                            'first' => array('value' => "宝宝家长您好",),
//                            'keyword1' => ARRAY('value' => date('Y年m月d'),),
//                            'keyword2' => ARRAY('value' => '您预约了' . date('Y年m月d', $day) . '的' . Appoint::$typeText[$v->type] . '，请按照预约时间到达社区'),
//                            'remark' => ARRAY('value' => "点击此处填写流行病学调查表，请酌情填写流行病学调查表，根据不同社区工作安排可能需要您出示调查结果，调查结果可以在公众号底部菜单我的->流行病学调查表中查看", 'color' => '#221d95'),
//                        ];
//                        $rs = WechatSendTmp::send($data, $openid, $temp, 'http://web.child.wedoctors.com.cn/question-naire/form?id=1&doctorid=' . $v->doctorid);
                    } else {
                        $data = [
                            'first' => array('value' => '您好，你预约了' . date('Y年m月d', $day) . ' 的 ' .Appoint::$typeText[$v->type]."，建议您在". Appoint::$timeText2[$v->appoint_time]  . '到达社区医院！',),
                            'keyword1' => ARRAY('value' => $v->name(),),
                            'keyword2' => ARRAY('value' => $hospital->name,),
                            'keyword3' => ARRAY('value' => '现场确认',),
                            'keyword4' => ARRAY('value' => date('Y年m月d', $day) . ' ' . Appoint::$timeText[$v->appoint_time]),
                            'keyword5' => ARRAY('value' => date('Y年m月d', $day) . ' ' . Appoint::$timeText2[$v->appoint_time]),
                            'remark' => ARRAY('value' => "再次温馨提醒，宫颈癌疫苗仅限本辖区居民预约，接种时务必携带本人本辖区的身份证或房产证或居住证(有效期内)，非本辖区居民预约后不予以接种。", 'color' => '#221d95'),
                        ];
                        if($v->type!=4) {
                            $data['remark'] = '此消息为系统自动推送，如已取消请忽略。';
                        }
                        $rs = WechatSendTmp::send($data, 'o5ODa0451fMb_sJ1D1T4YhYXDOcg', $temp, '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/appoint/view?id=' . $v->id,]);
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