<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/8/22
 * Time: 上午9:47
 */

namespace console\controllers;


use yii\base\Controller;

class TestController extends Controller
{
    public function actionEmail(){

        $mbox = imap_open ("{imap.163.com:993}INBOX", "etzyxm@163.com", "rowyJdD2MD");
        var_dump($mbox);exit;


    }

}