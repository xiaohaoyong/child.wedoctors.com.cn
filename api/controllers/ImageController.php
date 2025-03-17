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
            'version'        => 7,
            'scale' => 20, // 每个点的大小
            'margin'         => 2,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG, // 输出类型
            'foregroundColor' => ['r' => 0, 'g' => 0, 'b' => 0], // 前景色（黑色）
            'backgroundColor' => ['r' => 255, 'g' => 255, 'b' => 255], // 背景色（白色）
        ]);

        $qrCode = new QRCode($options);
        $data = 'appoint:'.$id;

// 输出二维码图像
        $base64Data= $qrCode->render($data);

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