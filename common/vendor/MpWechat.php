<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/3/1
 * Time: 上午10:18
 */

namespace common\vendor;

use Yii;

use yii\base\Event;
use yii\base\InvalidParamException;
use yii\web\HttpException;

class MpWechat extends \callmez\wechat\sdk\MpWechat
{
    /**
     * 发送模板消息
     */
    const WECHAT_TEMPLATE_MESSAGE_WXOPEN_SEND_PREFIX = '/cgi-bin/message/wxopen/template/send';
    /**
     * 发送模板消息
     * @param array $data 模板需要的数据
     * @return int|bool
     */
    public function sendTemplateMessage(array $data)
    {
        $result = $this->httpRaw(self::WECHAT_TEMPLATE_MESSAGE_WXOPEN_SEND_PREFIX, array_merge([

        ], $data), [
            'access_token' => $this->getAccessToken()
        ]);
        return isset($result['msgid']) ? $result['msgid'] : false;
    }
    const WECHAT_SUBSCRIBE_MESSAGE_WXOPEN_SEND_PREFIX = '/cgi-bin/message/subscribe/send';

    /**
     * 发送小程序订阅消息
     * @param array $data 模板需要的数据
     * @return int|bool
     */
    public function sendSubscribeMessage(array $data)
    {
        $result = $this->httpRaw(self::WECHAT_SUBSCRIBE_MESSAGE_WXOPEN_SEND_PREFIX, array_merge([

        ], $data), [
            'access_token' => $this->getAccessToken()
        ]);
       // var_dump($result);die;
        return isset($result['msgid']) ? $result['msgid'] : false;
    }
    public function delCache($name){
        return \Yii::$app->cache->delete($this->getCacheKey($name));
    }

    public function jsApiConfig(array $config = [])
    {
        $data = [
            'jsapi_ticket' => $this->getJsApiTicket(),
            'noncestr' => Yii::$app->security->generateRandomString(16),
            'timestamp' => time(),
            'url' => \Yii::$app->request->getAbsoluteUrl()
        ];
        return array_merge([
            'appId' => $this->appId,
            'timestamp' => $data['timestamp'],
            'nonceStr' => $data['noncestr'],
            'signature' => sha1(urldecode(http_build_query($data))),
            'jsApiList' => [
                'checkJsApi',
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
                'hideMenuItems',
                'showMenuItems',
                'hideAllNonBaseMenuItem',
                'showAllNonBaseMenuItem',
                'translateVoice',
                'startRecord',
                'stopRecord',
                'onRecordEnd',
                'playVoice',
                'pauseVoice',
                'stopVoice',
                'uploadVoice',
                'downloadVoice',
                'chooseImage',
                'previewImage',
                'uploadImage',
                'downloadImage',
                'getNetworkType',
                'openLocation',
                'getLocation',
                'hideOptionMenu',
                'showOptionMenu',
                'closeWindow',
                'scanQRCode',
                'chooseWXPay',
                'openProductSpecificView',
                'addCard',
                'chooseCard',
                'openCard'
            ]
        ], $config);
    }

}