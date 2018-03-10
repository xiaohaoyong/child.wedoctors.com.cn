<?php

namespace common\base;

use Yii;
use common\helpers\ArrayHelper;
use yii\helpers\Json;
use common\filters\AccessControl;
use yii\helpers\Url;
use callmez\wechat\sdk\MpWechat;
use common\helpers\StringHelper;
use common\models\UserLogin;

/**
 * api接口基础控制器类
 * 
 * @property int $uid 用户uid
 * @property \yii\web\User $user 用户登录对象
 * 
 */
class BaseWeixinController extends \yii\web\Controller {

    public $wxCodeName = "gb_WXcode_sheng";

    /**
     * 微信端
     */
    public $userInfo = [];

    /**
     * 本地
     */
    public $userData = [];

    /**
     * 响应对象
     * @var \yii\web\Response
     */
    public $response = null;

    /**
     * 请求对象
     * @var \yii\web\Request 
     */
    public $request = null;

    /**
     *  服务号 new Mpwechat
     */
    public $mpWechat = '';

    /**
     * 构造函数
     * @param type $id
     * @param type $module
     * @param array $config
     */
    function __construct($id, $module, $config = []) {
        parent::__construct($id, $module, $config = []);
        $this->_init();
    }

    /**
     * 初始化
     */
    private function _init() {
        $this->request = Yii::$app->request;
        $this->response = Yii::$app->response;
        Yii::$app->session->open();
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * 执行前
     * @param type $action
     * @return boolean
     */
    public function beforeAction($action) {

        if(\Yii::$app->request->get('we')=="new" || \Yii::$app->request->get('we')=='new')
        {
            $cookies = \Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
                'name' => 'we',
                'value' => 'new',
                'expire' =>time()+800000,
            ]));
        }

        $this->mpWechat = $this->newMpWechat();
        $openid = $this->getCookie('openid');
        //验证cookie
        if (empty($openid)) {
            if ($this->request->isGet) {
                $_code = Yii::$app->request->get('code');
                if (empty($_code)) {
                    $this->redirectOauth2Url();
                } else {
                    $getOauth2AccessToken = $this->mpWechat->getOauth2AccessToken($_code);
                    if (empty($getOauth2AccessToken['openid'])) {
                        echo $this->redirectMessage('openid获取失败!！');
                        exit;
                    }
                    $openid = $getOauth2AccessToken['openid'];
                }
                $this->addCookie('openid', $openid, 2592000);
            }
        }
        $this->userData = UserLogin::getInfo($openid);
        return TRUE;
    }

    /**
     * 获取用户信息微信端  重定向页面
     */
    protected function getWxCodeUserInfo($openid) {
        $this->userInfo = $this->mpWechat->getUserInfo($openid);
    }

    /**
     * 跳转一下获取微信code
     * 重定向页面
     */
    protected function redirectOauth2Url() {
        $_url = urlencode($this->request->hostInfo . $this->request->getUrl());
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1147c2e491dfdf1d&redirect_uri=' . $_url . '&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
        return $this->returnJson('50000', $url);exit();
//        header("Location:" . $url);
//        exit();
    }

    /**
     * 获取cookie
     * @param string $name
     * @return string
     */
    public function getCookie($name) {
        return $this->request->cookies->getValue($name);
    }

    /**
     * 添加cookie
     * @param string $name
     * @param string $value
     */
    public function addCookie($name, $value, $expire = 0) {
        $this->response->cookies->add(new \yii\web\Cookie([
            'name' => $name,
            'value' => $value,
            'expire' => time() + $expire,
        ]));
        Yii::$app->response->send();
    }

    /**
     *  服务号 new Mpwechat
     */
    private function newMpWechat() {
        $mpWechat = new MpWechat([
            'token' => \Yii::$app->params['WeToken'],
            'appId' => \Yii::$app->params['AppID'],
            'appSecret' => \Yii::$app->params['AppSecret'],
            'encodingAesKey' => \Yii::$app->params['encodingAesKey']
        ]);
        return $mpWechat;
    }

    /**
     *  返回json结果
     * 200正确11001错误
     */
    public function returnJson($code = 200, $msg = null, $data = null,$other=null) {
        $redata['code'] = $code;
        if ($data) {
            $redata['data'] = $data;
        }
        if ($msg) {
            $redata['msg'] = $msg;
        }
        if($other)
        {
            $redata=$redata+$other;
        }

        return json_encode($redata);
    }

}
