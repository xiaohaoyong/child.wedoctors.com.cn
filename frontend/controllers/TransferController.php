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
   public function actionIndex($p)
   {
       \Yii::$app->response->format = Response::FORMAT_JSON;
       $path = 'http://ebbhospital.binglang6.com/'.$p;
       $curl = new HttpRequest($path, true, 2);
       $userJson = $curl->get();
       return $userJson;
   }
}