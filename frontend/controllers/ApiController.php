<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/10/20
 * Time: 下午2:36
 */

namespace frontend\controllers;

use common\components\Code;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller
{
    private $result = [];

    public $enableCsrfValidation = false;
    public function beforeAction($action)
    {
        $path = \Yii::$app->request->pathInfo;

        \Yii::$app->response->format = Response::FORMAT_JSON;
        $post=\Yii::$app->request->post();
        $get=\Yii::$app->request->get();
        $debug=$get['debug'];
        $sign1=$get['sign'];
        unset($get['sign']);
        unset($get['debug']);

        $array=$get+$post;
        ksort($array);
        $sign=md5($path.http_build_query($array)."EX555Ji=i46H6;e7");
        if($debug==1){
            echo $sign;exit;
        }
        if(!$sign1 ||  $sign!=$sign1){
            \Yii::$app->response->data = ['code' => 30001, 'msg' => '数字签证错误'];
            return false;
        }else {
            return parent::beforeAction($action); // TODO: Change the autogenerated stub
        }
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