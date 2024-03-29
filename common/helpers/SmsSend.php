<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/11/7
 * Time: 上午11:21
 */

namespace common\helpers;

use common\components\HttpRequest;

class SmsSend
{


    public static function sendSms($mobile, $templatecode = 'SMS_63950776')
    {
        $str = \Yii::$app->cache->get($mobile);
        if (empty($code)) {
            $str = StringHelper::getRandStr(6, 1);
        }
        // $response = \Yii::$app->aliyun->sendSms(
        //     "儿宝宝", // 短信签名
        //     $templatecode, // 短信模板编号
        //     $mobile, // 短信接收者
        //     Array(  // 短信模板中字段的值
        //         "code"=>$str,
        //     )
        // );

        $curl = new HttpRequest('https://api.mix2.zthysms.com/v2/sendSmsTp', true, 10);
        $time = time();
        $curl->setData(json_encode([
            'username'=>'ebbhy',
            'password'=>md5(md5('307476qiW').$time),
            'tKey' =>$time,
            'signature'=>'【儿宝宝】',
            'tpId'=>'115025',
            'ext'=>'',
            'records'=>[
                'mobile'=>$mobile,
                'tpContent'=>[
                    'code'=>$str
                ],
            ],
        ]));
        $curl->setHeader('Content-Type','application/json');
        $response = $curl->post();
        //$user = json_decode($userJson, true);

        $response=json_decode($response,true);
        if ($response['code']==200) {
            //该验证码存cache
            $r=\Yii::$app->cache->set($mobile, $str, 900);
            \Yii::$app->cache->set("yz_" . $mobile, $str, 60);
        }
        return $response['code']==200?true:false;
    }
    public static function appoint($data,$mobile){
        $response = \Yii::$app->aliyun->sendSms(
            "儿宝宝", // 短信签名
            "SMS_151177324", // 短信模板编号
            $mobile, // 短信接收者
            Array(  // 短信模板中字段的值
                "doctor"=>$data['doctor'],
                "date_time"=>$data['date_time'],
                "type"=>$data['type'],
                "phone"=>$data['phone'],
            )
        );
        $response=json_decode($response,true);
        return $response['code']==200?true:false;
    }

    public static function notice($data,$mobile,$tmp){
        $response = \Yii::$app->aliyun->sendSms(
            "儿宝宝", // 短信签名
            $tmp, // 短信模板编号
            $mobile, // 短信接收者
            Array(  // 短信模板中字段的值
                "doctor"=>$data['doctor'],
                "phone"=>$data['phone'],
            )
        );
        $response=json_decode($response,true);
        return $response['code']==200?true:false;
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
            \Yii::$app->cache->delete($mobile);
            \Yii::$app->cache->delete("yz_" . $mobile);
            return json_encode(['code' => '200', 'msg' => 'success']);
        }
        return json_encode(['code' => '11001', 'msg' => '验证失败！']);
    }
}