<?php
/**
 * 全局控制器
 * User: wangzhen
 * Date: 2017/12/6
 * Time: 下午2:05
 */

namespace ask\controllers;


use common\components\Code;
use common\models\Login;
use common\models\User;
use common\models\UserInfo;
use common\models\UserLogin;
use yii\base\ActionEvent;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class Controller extends \yii\web\Controller
{
    private $result = ['notify/ask','user/login','user/wx-user-info','article/view','baby/collection-list','index/index'];
    protected $userid = 0;
    protected $user;
    protected $seaver_token;
    protected $appToken;
    protected $hxusername;
    protected $userLogin;
    protected $version;

    public function beforeAction($action)
    {
        \Yii::$app->response->format=Response::FORMAT_JSON;

        $this->seaver_token=\Yii::$app->request->headers->get('sessionkey');
        $this->version=\Yii::$app->request->headers->get('version');


        $controllerID = \Yii::$app->controller->id;
        $actionID = \Yii::$app->controller->action->id;

        if($this->seaver_token)
        {
            $cache=\Yii::$app->rdmp;
            $session=$cache->get($this->seaver_token);
            $session=explode('@@',$session);
            if( $session[0]) {
                $userLogin = UserLogin::findOne(['aopenid' => $session[0]]);
                if (!$userLogin && !in_array($controllerID . "/" . $actionID, $this->result)) {
                    $cache = \Yii::$app->rdmp;
                    $cache->lpush("user_login_error", $session[0]);
                    \Yii::$app->response->data = ['code' => 30001, 'msg' => '未授权访问'];
                    return false;
                }
                $this->userid = $userLogin->userid;
                $this->user = $userLogin->user;
                $this->appToken = $session;
                $this->userLogin = $userLogin;
            }else{
                \Yii::$app->response->data = ['code' => 30001, 'msg' => '未授权访问'];
                return false;
            }
        }elseif(!in_array($controllerID."/".$actionID,$this->result)){
            \Yii::$app->response->data = ['code' => 30001,'msg' => '数字签证错误'];
            return false;
        }


        /*
        if(!$seaver_token)
        {
            \Yii::$app->response->data = ['code' => 30001,'msg' => '数字签证错误'];
            return false;
        }*/

        //预留，判断签名是否正确

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }


    public function afterAction($action, $result)
    {
        $return = parent::afterAction($action, $result); // TODO: Change the autogenerated

        $return=$this->return_array_filter($return);
        if($result instanceof Code && $result->getCode())
        {
            return $result->getCode();
        }

        $this->result=Code::result();
        if (!isset($return['data']) && $return) {
            $this->result['data'] = $return;
        }elseif (is_array($return)) {
            if($return) {
                foreach ($return as $k => $v) {
                    if ($k == 'data' || $k == 'msg' || $k == 'code') {
                        $this->result[$k] = $v;
                    } else {
                        $this->result['data'][$k] = $v;
                    }
                }
            }else{
                $this->result['data'] = [];
            }
        } else {
            if (!isset($this->result['data'])) unset($this->result['data']);
        }
        return $this->result;
    }

    private function return_array_filter($return)
    {
        if(!is_array($return)) return $return;
        $data=[];
        foreach($return as $k=>$v)
        {
            if(is_array($v))
            {
                $row=$this->return_array_filter($v);
            }else{
                $row= $v===null?"":$v;
            }
            $data[$k]=$row;
        }
        return $data;
    }

}