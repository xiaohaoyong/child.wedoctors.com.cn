<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/23
 * Time: 下午4:33
 */

namespace api\controllers;


use common\components\HttpRequest;

class SiteController extends \yii\web\Controller
{
    public function actionIndex(){

        $curl = new HttpRequest( "http://toc.minganonline.com/pages/everydayRead.html?SHARE_LEVEL=1", true, 2);
        $html=$curl->get();
        $html=str_replace('../','https://toc.minganonline.com/',$html);
        return $this->renderPartial('index',['html'=>$html]);
    }

}