<?php
/**
 * 短信接口
 * User: wangzhen
 * Date: 2019/4/10
 * Time: 上午11:14
 */
namespace interfaces\controllers;


use common\components\Code;

class SmsController extends Controller
{
    public static $type=[
        1=>'SMS_163051604',//咨询建议
        2=>'SMS_163051498',//已接诊
        3=>'SMS_163056341',//支付成功
    ];
    //发送短信模板
    public function actionPhoneDoctor($phone,$type){
        $params=\Yii::$app->request->post();
        if(self::$type[$type]) {
            $response = \Yii::$app->aliyun->sendSms(
                "儿宝宝", // 短信签名
                self::$type[$type], // 短信模板编号
                $phone, // 短信接收者
                $params
            );
        }
        $response=json_decode($response,true);
        if($response['code']!=200){
            return new Code(20010,$response['message']);
        }
    }
}