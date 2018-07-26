<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/20
 * Time: 下午2:46
 */

namespace api\controllers;


use common\components\HttpRequest;
use common\helpers\HuanxinHelper;
use common\helpers\WechatSendTmp;

class TextController extends Controller
{

    public function actionText(){
        $data = [
            'first' => array('value' => "您好，为确保享受儿童中医健康指导服务,请尽快完善宝宝信息\n",),
            'keyword1' => ARRAY('value' => "宝宝基本信息"),
            'keyword2' => ARRAY('value' => "123123123"),
            'remark' => ARRAY('value' => "\n 点击完善宝宝信息", 'color' => '#221d95'),
        ];
        $rs = WechatSendTmp::send($data, 'o5ODa0451fMb_sJ1D1T4YhYXDOcg','wiVMfEAlt4wYwfpjcawOTDwgUN8SRPIH1Fc8wVWfGEI', '',['appid'=>\Yii::$app->params['wxXAppId'],'pagepath'=>'pages/index/index',]);
        echo "\n";
    }


    public function actionMessage(){
        HuanxinHelper::setTxtMessage('wangzhentest','7aaf57f87f62e2ae8c1e644fe734344b','撒大声地as阿斯蒂芬阿斯蒂芬阿萨德发手动阀是对方发送发斯蒂芬阿斯蒂芬阿斯蒂芬阿萨德发手动阀撒大声地as阿斯蒂芬阿斯蒂芬阿萨德发手动阀是对方发送发斯蒂芬阿斯蒂芬阿斯蒂芬阿萨德发手动阀撒大声地as阿斯蒂芬阿斯蒂芬阿萨德发手动阀是对方发送发斯蒂芬阿斯蒂芬阿斯蒂芬阿萨德发手动阀');
    }

    public function actionWangye(){


        $curl = new HttpRequest('https://dev.minganmed.com/pages/everydayRead.html', true, 10);
        $content = $curl->get();

        $content=str_replace('../','https://toc.minganonline.com/',$content);
        $content=str_replace('https://toc.minganonline.com/static/js/lib/common.js','https://api.child.wedoctors.com.cn/js/common.js',$content);

        echo $content;
        exit;
    }


}