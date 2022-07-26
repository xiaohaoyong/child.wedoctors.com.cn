<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/6/12
 * Time: 下午4:43
 */

namespace console\controllers;


use common\helpers\WechatSendTmp;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Examination;
use common\models\MpEventPush;
use common\models\Pregnancy;
use common\models\PushLog;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\WeOpenid;
use console\models\ChildInfoInput;
use console\models\ExInput;
use EasyWeChat\Factory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Settings;
use yii\base\Controller;
use yii\redis\Cache;

class EventPushController extends Controller
{
    public function actionGroup()
    {
        $config = [
            113890=>[2=>2228,3=>2229]
        ];
        $weopenid = MpEventPush::find()
            ->andWhere(['>', 'createtime', strtotime('-2 hours')])
            //->andWhere(['openid'=>'o5ODa0451fMb_sJ1D1T4YhYXDOcg'])
            ->all();
        foreach ($weopenid as $k => $v) {
            list($type,$doctorid)=$this->level($v->openid);
            if ($type && $doctorid) {
                $doctor=UserDoctor::findOne(['userid'=>$doctorid]);

                $data = [
                    'first' => array('value' => $doctor->name."邀请您添加一对一小助手\n",),
                    'keyword1' => ARRAY('value' => "儿宝宝用户"),
                    'keyword2' => ARRAY('value' => date('Y年m月d H:i')),
                    'keyword3' => ARRAY('value' => '儿宝宝一对一小助手'),
                    'remark' => ARRAY('value' => "\n 点击查看小助手二维码", 'color' => '#221d95')
                ];

                $temp='VXAAPM2bzk1zGHAOnj8cforjriNp3wsg4ZewGEUck_0';

                if($config[$doctorid][$type]) {
                    $miniprogram = [
                        "appid" => \Yii::$app->params['wxXAppId'],
                        "pagepath" => "/pages/article/view/index?id=" . $config[$doctorid][$type],
                    ];
                    $rs = WechatSendTmp::send($data, $v->openid, $temp, '', $miniprogram,$config[$doctorid][$type]);
                    var_dump($rs);

                    $pushLog = new PushLog();
                    $pushLog->openid = $v->openid;
                    $pushLog->type = $type;
                    $pushLog->doctorid=$doctorid;
                    $pushLog->return = 1;
                    if (!$pushLog->save()) {
                        var_dump($pushLog->firstErrors);
                        exit;
                    }
                    echo $v->openid;
                    echo "\n";
                    sleep(5);
                }
            }

        }

    }
    public function level($openid){

        $pushLog=PushLog::find()->andWhere(['type'=>2])->andWhere(['openid'=>$openid])->one();
        if(!$pushLog) {
            $userLogin = UserLogin::find()->where(['openid' => $openid])->one();
            if ($userLogin) {
                $child = ChildInfo::find()->where(['userid' => $userLogin->userid])
                    ->andWhere(['>', 'birthday', strtotime('-7 year')])->one();
                $doctorParent=DoctorParent::findOne(['parentid'=>$userLogin->userid]);
                if ($child and $doctorParent) {
                    return [2,$doctorParent->doctorid];
                }
            }
        }
        $pushLog=PushLog::find()->andWhere(['type'=>3])->andWhere(['openid'=>$openid])->one();
        if(!$pushLog) {
            $userLogin = UserLogin::find()->where(['openid' => $openid])->one();
            if ($userLogin) {
                $prog=Pregnancy::find()
                    ->where(['familyid' => $userLogin->userid])
                    ->andWhere(['>','field11', strtotime('-10 month')])
                    ->andWhere(['!=','field49',1])->one();
                $doctorParent=DoctorParent::findOne(['parentid'=>$userLogin->userid]);
                if ($prog and $doctorParent) {
                    return [3,$doctorParent->doctorid];
                }
            }
        }

        return [false,false];
    }
}