<?php

namespace common\helpers;

use Yii;
use gmars\sms\Sms;
use common\helpers\StringHelper;

/**
 * 扩展框发送验证码
 *
 * @author xusheng <332087024@qq.com>
 */
class SendHelper {

    /**
     * 发送验证码
     * @param string $name
     * @param string $value
     * @param string $expire 秒
     */
    public static function sendSms($mobile, $templatecode = 'SMS_63950776') {
        $str = \Yii::$app->cache->get($mobile);
        if (empty($code)) {
            $str = StringHelper::getRandStr(4, 1);
        }
        //注意此方法中data中必填code product两个参数 其余参数无定制 如需修改请改底层代码或联系许升
        $smsObj = new Sms('ALIDAYU', ['appkey' => '23785111', 'secretkey' => 'b98c1d4ee116f7f8316750542d49f914']);
        $sendData = $smsObj->send([
            'mobile' => $mobile,
            'signname' => '儿宝宝',
            'templatecode' => $templatecode,
            'data' => [
                'code' => $str, //验证吗
                'product' => '儿宝宝'//
            ],
        ]);
        //$sendData = json_decode($sendData, true);
        if ($sendData) {
            //该验证码存cache
            \Yii::$app->cache->set($mobile, $str, 900);
            \Yii::$app->cache->set("yz_" . $mobile, $str, 60);
        }
        return $sendData;
    }

    
    /**
     * 短信验证
     * 接收$phone 手机号, $verify验证码
     */
    public static function verifyMessage($mobile, $verify) {
        //获得该手机号的验证码
        //从Memcache中获取该手机号验证码
        $code = \Yii::$app->cache->get($mobile);
        if ($code == $verify) {
            Yii::$app->cache->delete($mobile);
            Yii::$app->cache->delete("yz_" . $mobile);
            return json_encode(['code' => '200', 'msg' => 'success']);
        }
        return json_encode(['code' => '11001', 'msg' => '验证失败！']);
    }

}

?>
