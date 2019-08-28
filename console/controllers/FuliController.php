<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/6/12
 * Time: 下午4:43
 */

namespace console\controllers;


use common\models\ChildInfo;
use common\models\Examination;
use common\models\PushLog;
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

class FuliController extends Controller
{
    public function actionSubscribe()
    {
        $config = [
            'app_id' => 'wx1147c2e491dfdf1d',
            'secret' => '98001ba41e010dea2861f3e0d95cbb15',
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            //...
        ];
        $weopenid = WeOpenid::find()->where(['>', 'createtime', strtotime('-5 hours')])
            ->andWhere(['<', 'createtime', strtotime('-2 hours')])
            ->andWhere(['!=', 'openid', ''])
            ->all();
        foreach ($weopenid as $k => $v) {
            if ($this->level($v->openid) > 0) {

                $app = Factory::officialAccount($config);
                $rs = $app->customer_service
                    ->message('儿宝宝福利社上线啦，我们会定期给家长们搜罗些免费、实用、有趣的福利哦！' . "\n" . '<a href="http://child.wedoctors.com.cn" data-miniprogram-appid="wx6c33bfd66eb0a4f0" data-miniprogram-path="pages/index/index">快快来领取吧</a>')
                    ->to($v->openid)->send();
                $return=1;
                $pushLog=new PushLog();
                $pushLog->openid=$v->openid;
                $pushLog->type=1;
                $pushLog->return=$rs['errcode'];
                if(!$pushLog->save()){
                    var_dump($pushLog->firstErrors);
                    exit;
                }
                echo $v->openid;
                echo "\n";
                sleep(5);
            }

        }

        var_dump($rs['errcode']);
    }

    public function level($openid)
    {
        $pushLog=PushLog::find()->andWhere(['type'=>1])->andWhere(['openid'=>$openid])->one();
        if(!$pushLog) {
            $userLogin = UserLogin::find()->where(['openid' => $openid])->one();
            if ($userLogin) {
                $child = ChildInfo::find()->where(['userid' => $userLogin->userid])
                    ->andWhere(['>', 'birthday', strtotime('-3 year')])->one();
                if ($child) {
                    return 1;
                }
            }
        }
        return 0;
    }
}