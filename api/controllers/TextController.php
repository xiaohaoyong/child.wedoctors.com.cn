<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/20
 * Time: 下午2:46
 */

namespace api\controllers;

use api\controllers\Controller;

use common\components\HttpRequest;
use common\helpers\HuanxinHelper;

class TextController extends Controller
{

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