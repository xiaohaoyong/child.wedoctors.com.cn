<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/23
 * Time: 下午4:33
 */

namespace api\controllers;

use api\controllers\Controller;

use app\components\UploadForm;
use common\components\HttpRequest;
use common\components\Log;
use common\models\WeMessage;
use yii\web\UploadedFile;

class SiteController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $postStr = file_get_contents("php://input");
        $xml = simplexml_load_string($postStr, null, LIBXML_NOCDATA);
        $xmlArray = json_encode($xml);
        $xmlArray = json_decode($xmlArray, true);

        if($xmlArray['FromUserName'])
        {
            $weMessage=new WeMessage();
            $weMessage->load(['Wemessage'=>$xmlArray]);
            $weMessage->save();
        }
        $log=new Log('messagex');
        $log->addLog(json_encode($xmlArray));
        $log->addLog(json_encode($weMessage->firstErrors));

        $log->saveLog();

        $template = <<<XML
 <xml>
     <ToUserName><![CDATA[%s]]></ToUserName>
     <FromUserName><![CDATA[%s]]></FromUserName>
     <CreateTime>%s</CreateTime>
     <MsgType><![CDATA[transfer_customer_service]]></MsgType>
 </xml>
XML;
        return sprintf($template, $xmlArray['FromUserName'], $xmlArray['ToUserName'], time());
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    public function actionSaveImage()
    {
        $imagesFile = UploadedFile::getInstancesByName('file');
        if ($imagesFile) {
            $upload = new \common\components\UploadForm();
            $upload->imageFiles = $imagesFile;
            $image = $upload->upload();
        }
        return $image;
    }

    public function actionText()
    {
        // Include the main TCPDF library (search for installation path).

// create new PDF document
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); //设置默认等宽字体
// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT); //设置margins 参考css的margins
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER); //设置页头margins
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER); //设置页脚margins
// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM); //设置自动分页
        $pdf->setPrintHeader(false);    //页面头部横线取消
        $pdf->setPrintFooter(false); //页面底部更显取消

// ---------------------------------------------------------
// set default font subsetting mode
        $pdf->setFontSubsetting(true); //设置默认字体子集模式
// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
        $pdf->SetFont('stsongstdlight', '', 14, '', true); //设置字体
// Add a page
// This method has several options, check the source code documentation for more information.
        $pdf->AddPage(); //增加一个页面
// set text shadow effect  设置文字阴影效果

// Set some content to print
        $html = <<<EOD
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>北京市朝阳区家庭医生签约服务协议书</title>
</head>
<style>
    body {
        font-size: 20px;
        text-align: left;
        max-width: 100%;
        margin: auto;
    }

    h1 {
        text-align: center;
    }

    .content {
        font-size: 12px;
        line-height: 15px;
    }
</style>
<body>
<h1>北京市朝阳区家庭医生签约服务协议书</h1>
<div class="content">
    <div class="content1">
        <p>乙方(家庭成员代表):</p>
        <p>家庭电话:</p>
        <p>住 址:</p>
        <p>家庭健康档案号:</p>
        <p>甲方:朝阳区xxx家庭医生签约服务团队</p>
        <p>团队长固定电话:</p>
        <p>健康通手机:</p>
        <p> </p>
        <p style="text-indent:25px;">甲、乙双方本着平等、尊重和自愿的原则，签订此协议，接受以下条的约定:</p>
        <p style="text-indent:25px;">一 、根据乙方基本医疗卫生服务需求和甲方服务能力，由甲方 为乙方提供以下基本医疗、基本公共卫生及健康管理服务。(请在具体服务项目的□上进行√勾选，可多选):</p>
        <p> </p>
        <p style="text-indent:25px;">√ 1  . 0-6岁儿童服务项目</p>
        <p style="text-indent:25px;">□ 2  . 孕产妇服务项目</p>
        <p style="text-indent:25px;">□ 3  . 65 岁及以上老年人服务项目
        <p style="text-indent:25px;">□ 4  . 托底/扶助老人服务项目</p>
        <p style="text-indent:25px;">□ 5  . 肺结核患者服务项目</p>
        <p style="text-indent:25px;">□ 6  . 严重精神障碍患者服务项目
        <p style="text-indent:25px;">□ 7  . 残疾人服务项目</p>
        <p style="text-indent:25px;">□ 8  . 高血压患者服务项目</p>
        <p style="text-indent:25px;">□ 9  . 糖尿病患者服务项目</p>
        <p style="text-indent:25px;">□ 10 . 冠心病患者服务项目</p>
        <p style="text-indent:25px;">□ 11 . 脑卒中患者服务项目</p>
        <p style="text-indent:25px;">□ 12 . 一般人群服务项目</p>
        <p> </p>
        <p style="text-indent:25px;">二、乙方及其家庭成员自愿接受以上所选服务，将自己的身体健康状况及变化情况
        及时告知甲方，并保证沟通畅通，积极配合甲方的服务。</p>
        <p style="text-indent:25px;">本协议书一式两份，甲方、乙方各执一份，自双方签字之日起生效，有效期为5年。
        期满后如需解约，乙方需告知甲方，双方签字确认。不提出解约视为自动续约，
        (乙方可通过儿宝宝平台进行电子签约)。</p>
    </div>
</div>
</body>
</html>
EOD;

        /*
         *
          此方法允许以换行符打印文本。
          它们可以是自动的（一旦文本到达单元格的右边界）或显式（通过\ n字符）。 输出所需的许多单元，一个低于另一个。
            文本可以对齐，居中或对齐。 单元格块可以框架并绘制背景。
         */

// Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true); //使用writeHTMLCell打印文本

        $pdf->Write(15,'甲方:                                                                                         乙方:', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(15,'2018年10月01日                                                                   2018年10月01日', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(15,' ', '', 0, 'L', true, 0, false, false, 0);

        $pdf->Write(15,'（  解约时间：                                                                     解约原因：', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(15,'       甲方确认：                                                                     乙方确认：                             ）', '', 0, 'L', true, 0, false, false, 0);


// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
        $pdf->Output('example_001.pdf', 'I'); //I输出在浏览器上

//============================================================+
// END OF FILE
//============================================================+
    }

}