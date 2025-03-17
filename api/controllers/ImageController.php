<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/10/31
 * Time: 下午5:38
 */

namespace api\controllers;


use common\models\UserDoctor;
use Da\QrCode\QrCode;


class ImageController extends \yii\web\Controller
{
    public function actionQrCode($id){

        //QrCode::png('appoint:'.$id,false,Enum::QR_ECLEVEL_H,10);
        $qrCode = (new QrCode('appoint:'.$id))
            ->setSize(250)
        ->setEncoding('BINARY'); // 强制二进制编码


        // 获取Base64编码的图片数据（假设通过GET参数传递）
        $base64Data = $qrCode->writeDataUri();

// 检查数据是否包含data URI前缀
        if (strpos($base64Data, 'data:') !== 0) {
            die('无效的Base64数据格式，需以data:开头');
        }

// 分割MIME类型和编码数据
        $parts = explode(',', $base64Data, 2);
        if (count($parts) !== 2) {
            die('Base64数据格式错误');
        }

// 提取MIME类型（例如：image/png）
        $mimeType = substr(explode(';', $parts[0])[0], 5); // 去除"data:"
        $encodedData = $parts[1];

// 解码Base64数据
        $decodedData = base64_decode($encodedData);
        if ($decodedData === false) {
            die('Base64解码失败');
        }

// 设置图片类型头并输出
        header('Content-Type: ' . $mimeType);
        echo $decodedData;

// 确保脚本终止，避免额外输出
        exit;
    }

    public function actionDoctor($id){
        QrCode::png('http://web.child.wedoctors.com.cn/health-records/form?doctorid='.$id,false,Enum::QR_ECLEVEL_H,10);exit;
    }
    public function actionDoctor2($id){
        QrCode::png('http://web.child.wedoctors.com.cn/health-records/form2?doctorid='.$id,false,Enum::QR_ECLEVEL_H,10);exit;
    }

}