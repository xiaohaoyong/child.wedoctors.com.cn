<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/10/31
 * Time: 下午5:38
 */

namespace api\controllers;


use common\models\UserDoctor;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class ImageController extends \yii\web\Controller
{
    public function actionQrCode($id){

        // 创建 QRCode 对象
        $options = new QROptions([
            'eccLevel' => QRCode::ECC_L, // 容错率
            'scale' => 5, // 每个点的大小
            'outputType' => QRCode::OUTPUT_IMAGE_PNG, // 输出类型
            'foregroundColor' => ['r' => 0, 'g' => 0, 'b' => 0], // 前景色（黑色）
            'backgroundColor' => ['r' => 255, 'g' => 255, 'b' => 255], // 背景色（白色）
        ]);

        $qrCode = new QRCode('appoint:'.$id);
        $data = '这是一个二维码内容';

// 输出二维码图像
        header('Content-Type: image/png');
        echo $qrCode->render($data);
    }

    public function actionDoctor($id){
        QrCode::png('http://web.child.wedoctors.com.cn/health-records/form?doctorid='.$id,false,Enum::QR_ECLEVEL_H,10);exit;
    }
    public function actionDoctor2($id){
        QrCode::png('http://web.child.wedoctors.com.cn/health-records/form2?doctorid='.$id,false,Enum::QR_ECLEVEL_H,10);exit;
    }

}