<?php
/**
 * 全局控制器
 * User: wangzhen
 * Date: 2017/12/6
 * Time: 下午2:05
 */

namespace api\controllers;


use common\components\Code;
use common\models\Autograph;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Log;
use common\models\Pregnancy;
use common\models\User;
use common\models\UserDoctor;
use common\models\UserInfo;
use common\models\UserLogin;
use yii\base\ActionEvent;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class Controller extends \yii\web\Controller
{
    private $result = ['agreement/content','user/login','user/code','user/phone-login', 'user/wx-user-info', 
                        'article/view', 'baby/collection-list', 'text/text','doctor/row','child/new-list'
                        ,'notice/index','family-doctor-services/index','doctor/view','clock-in/user','article/list','article/new-index','article/new-list'
                        ,'article/index','article/view','comment/list','data/article-view','baby/vlist','baby/list-new','baby/tag','baby/vview','data/vaccine','my/index'];
    private $autoResult = ['agreement/content','user/save-image', 'user/login'];
    protected $userid = 0;
    protected $user;
    protected $seaver_token;
    protected $appToken;
    protected $hxusername;
    protected $userLogin;
    protected $version;

    public function beforeAction($action)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $this->seaver_token = \Yii::$app->request->headers->get('sessionkey');
        $this->hxusername = \Yii::$app->request->headers->get('hxusername');
        $this->version = \Yii::$app->request->headers->get('version');

        $controllerID = \Yii::$app->controller->id;
        $actionID = \Yii::$app->controller->action->id;

        $log = new \common\components\Log('controller');
        $rs[] = $this->seaver_token;
        $log->addLog(json_encode($rs));
        $log->saveLog();

       // $this->userid = 1;

        if ($this->seaver_token) {
            $cache = \Yii::$app->rdmp;
            $session = $cache->get($this->seaver_token);
            $session = explode('@@', $session);
            if ($session[0]) {
                $userLogin = UserLogin::findOne(['xopenid' => $session[0]]);

                if (!$userLogin && !in_array($controllerID . "/" . $actionID, $this->result)) {
                    $cache = \Yii::$app->rdmp;
                    $cache->lpush("user_login_error", $session[0]);
                    \Yii::$app->response->data = ['code' => 30001, 'msg' => '未授权访问'];
                    return false;
                }else{
                    $this->userid = $userLogin->userid;
                    $this->user = $userLogin->user;
                    $this->appToken = $session;
                    $this->userLogin = $userLogin;
                }
                //判断是否签名
                if ($this->userid && !in_array($controllerID . "/" . $actionID, $this->autoResult)) {
                    $doctorParent = DoctorParent::findOne(['parentid' => $this->userid]);
                    if ($doctorParent) {
                        $doctor = UserDoctor::findOne(['userid' => $doctorParent->doctorid]);
                        if(in_array($doctor->county,[1106,1105,1109,1116,1113,1112]) || in_array($doctorParent->doctorid,[620548,154815,143296,118080,126118,126122,160226,113896,113478,91722,175877,176156,184741,184793,190922,192821,194606,206259,206262,206260,213579,213390,213581,216592,216593,216594,219333,221895,223413,228039,240074,240185,240188,240189,240191,248035,248033,281082,281083,281085,281087,281091,281092,281097])) {
                            $auto = Autograph::findOne(['userid' => $this->userid]);
                            if (!$auto) {
                                //判断是否添加了宝宝或者孕产妇
                                $child = ChildInfo::findOne(['userid' => $this->userid]);
                                $preg = Pregnancy::findOne(['familyid' => $this->userid]);
                                if ($child || $preg) {
                                    \Yii::$app->response->data = ['code' => 30002, 'msg' => '已签约未签字'.$this->userid];
                                    return false;
                                }
                            }
                        }
                    }
                }
            }elseif (!in_array($controllerID . "/" . $actionID, $this->result)) {
                \Yii::$app->response->data = ['code' => 30001, 'msg' => '数字签证错误'];
                return false;
            }

        } elseif (!in_array($controllerID . "/" . $actionID, $this->result)) {
            \Yii::$app->response->data = ['code' => 30001, 'msg' => '数字签证错误'];
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

        $return = $this->return_array_filter($return);
        if ($result instanceof Code && $result->getCode()) {
            return $result->getCode();
        }

        $this->result = Code::result();
        if (!isset($return['data']) && $return) {
            $this->result['data'] = $return;
        } elseif (is_array($return)) {
            if ($return) {
                foreach ($return as $k => $v) {
                    if ($k == 'data' || $k == 'msg' || $k == 'code') {
                        $this->result[$k] = $v;
                    } else {
                        $this->result['data'][$k] = $v;
                    }
                }
            } else {
                $this->result['data'] = [];
            }
        } else {
            if (!isset($this->result['data'])) unset($this->result['data']);
        }
        return $this->result;
    }

    private function return_array_filter($return)
    {
        if (!is_array($return)) return $return;
        $data = [];
        foreach ($return as $k => $v) {
            if (is_array($v)) {
                $row = $this->return_array_filter($v);
            } else {
                $row = $v === null ? "" : $v;
            }
            $data[$k] = $row;
        }
        return $data;
    }

}