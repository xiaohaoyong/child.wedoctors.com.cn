<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/3/1
 * Time: 上午10:18
 */

namespace common\vendor;


use yii\base\Event;
use yii\base\InvalidParamException;
use yii\web\HttpException;

class MpWechat extends \callmez\wechat\sdk\MpWechat
{
    private $x_accessToken;

    /**
     * 获取AccessToken
     * 超时后会自动重新获取AccessToken并触发self::EVENT_AFTER_ACCESS_TOKEN_UPDATE事件
     * @param bool $force 是否强制获取
     * @return mixed
     * @throws HttpException
     */
    public function getAccessToken($force = false)
    {
        $time = time(); // 为了更精确控制.取当前时间计算
        if ($this->x_accessToken === null || $this->x_accessToken['expire'] < $time || $force) {
            $result = $this->x_accessToken === null && !$force ? $this->getCache('xaccess_token', false) : false;
            if ($result === false) {
                if (!($result = $this->requestAccessToken())) {
                    throw new HttpException(500, 'Fail to get access_token from wechat server.');
                }
                $result['expire'] = $time + $result['expires_in'];
                $this->trigger(self::EVENT_AFTER_ACCESS_TOKEN_UPDATE, new Event(['data' => $result]));
                $this->setCache('xaccess_token', $result, $result['expires_in']);
            }
            $this->setAccessToken($result);
        }
        return $this->x_accessToken['access_token'];
    }

    /**
     * 设置AccessToken
     * @param array $accessToken
     * @throws InvalidParamException
     */
    public function setAccessToken(array $accessToken)
    {
        if (!isset($accessToken['access_token'])) {
            throw new InvalidParamException('The wechat access_token must be set.');
        } elseif(!isset($accessToken['expire'])) {
            throw new InvalidParamException('Wechat access_token expire time must be set.');
        }
        $this->x_accessToken = $accessToken;
    }
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
        var_dump($result);exit;
        return isset($result['msgid']) ? $result['msgid'] : false;
    }
}