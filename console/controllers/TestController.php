<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/8/22
 * Time: 上午9:47
 */

namespace console\controllers;


use common\models\ChildInfo;
use yii\base\Controller;

class TestController extends Controller
{
    public function actionChildType(){
        $childInfo = ChildInfo::find()->andFilterWhere(['userid' => 83624])->one();
        var_dump($childInfo->getType());

    }
    public function actionEmail(){

        $mbox = imap_open ("{imap.163.com:993}INBOX", "etzyxm@163.com", "rowyJdD2MD");
        var_dump($mbox);exit;


    }

}