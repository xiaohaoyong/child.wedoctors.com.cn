<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/10/31
 * Time: 下午5:38
 */

namespace api\controllers;


use common\models\UserDoctor;
use dosamigos\qrcode\lib\Enum;
use dosamigos\qrcode\QrCode;

class ImageController extends \yii\web\Controller
{
    public function actionQrCode($id){
        QrCode::png('appoint:'.$id,false,Enum::QR_ECLEVEL_H,10);exit;
    }

    public function actionDoctor($id){
        QrCode::png('http://web.child.wedoctors.com.cn/health-records/form?doctorid='.$id,false,Enum::QR_ECLEVEL_H,10);exit;
    }
}