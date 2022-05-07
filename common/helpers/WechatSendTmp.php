<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 17/10/17
 * Time: 下午5:06
 */

namespace common\helpers;


use callmez\wechat\sdk\MpWechat;

class WechatSendTmp
{



    public static function send($data,$touser,$tmpid,$url="",$miniprogram=[])
    {
        $push_data['data'] = $data;
        $push_data['touser'] = $touser;
        $push_data['template_id'] = $tmpid;
        $push_data['url'] = $url;
        if($miniprogram)
        {
            $push_data['miniprogram']=$miniprogram;
        }

        $mpWechat = new MpWechat([
            'token' => \Yii::$app->params['WeToken'],
            'appId' => \Yii::$app->params['AppID'],
            'appSecret' => \Yii::$app->params['AppSecret'],
            'encodingAesKey' => \Yii::$app->params['encodingAesKey']
        ]);
        $redis=\Yii::$app->rd0;
        $key='wechat-'.$touser.$tmpid;
        var_dump($redis->GET($key));
        if(!$redis->GET($key)) {
            $redis->SET($key, 1);
            $time=strtotime(date('Ymd',strtotime('+1 day')))-time();
            $redis->EXPIRE($key,$time);
            return $mpWechat->sendTemplateMessage($push_data);

        }else{
            return false;
        }
    }

    public static function sendMessage($data)
    {
        $mpWechat = new MpWechat([
            'token' => \Yii::$app->params['WeToken'],
            'appId' => \Yii::$app->params['AppID'],
            'appSecret' => \Yii::$app->params['AppSecret'],
            'encodingAesKey' => \Yii::$app->params['encodingAesKey']
        ]);
        return $mpWechat->sendMessage($data);
    }

    public static function sendX($data,$touser,$tmpid,$url="",$formid){
        $push_data['data'] = $data;
        $push_data['touser'] = $touser;
        $push_data['template_id'] = $tmpid;
        $push_data['page'] = $url;
        $push_data['form_id']=$formid;

        $mpWechat = new \common\vendor\MpWechat([
            'token' => \Yii::$app->params['WeToken'],
            'appId' => \Yii::$app->params['wxXAppId'],
            'appSecret' => \Yii::$app->params['wxXAppSecret'],
            'encodingAesKey' => \Yii::$app->params['encodingAesKey'],
        ]);
        return $mpWechat->sendTemplateMessage($push_data);
    }
    public static function sendSubscribe($data,$touser,$tmpid,$url=""){
        $push_data['data'] = $data;
        $push_data['touser'] = $touser;
        $push_data['template_id'] = $tmpid;
        $push_data['page'] = $url;
        $push_data['miniprogram_state']='trial';

        $mpWechat = new \common\vendor\MpWechat([
            'token' => \Yii::$app->params['WeToken'],
            'appId' => \Yii::$app->params['wxXAppId'],
            'appSecret' => \Yii::$app->params['wxXAppSecret'],
            'encodingAesKey' => \Yii::$app->params['encodingAesKey'],
        ]);
        return $mpWechat->sendSubscribeMessage($push_data);
    }
}