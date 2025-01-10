<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace frontend\controllers;

use api\models\ChildInfo;
use common\components\Code;
use common\components\HttpRequest;
use common\components\Log;
use common\helpers\SmsSend;
use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\AppointAdult;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointWeek;
use common\models\UserDoctor;
use common\models\Vaccine;
use yii\base\Application;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;
use common\models\AppointCalling;
use common\models\AppointCallingList;
use common\models\queuing\Queue;

class TransferController extends Controller
{
   public function actionIndex()
   {
       \Yii::$app->response->format = Response::FORMAT_JSON;
       $params = \Yii::$app->request->queryParams;
       $authorization = \Yii::$app->request->headers->get('Authorization');

       $p = $params['p'];
       $method = $params['method'];
       unset($params['p']);
       unset($params['method']);


       if($method == 'get'){
           $url = http_build_query($params);
           $path = 'http://interface.ebb.wedoctors.com.cn/'.$p.'?'.$url;
           $curl = new HttpRequest($path, true, 2);
       }else{
           $path = 'http://interface.ebb.wedoctors.com.cn/'.$p;
           $curl = new HttpRequest($path, true, 2);
           $curl->setData($params);
       }
       if($authorization) {
           $curl->setHeader('Authorization', $authorization);
       }
       echo $path;exit;
       $userJson = $curl->$method();
       return json_decode($userJson,true);
   }
}