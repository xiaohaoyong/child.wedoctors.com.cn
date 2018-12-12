<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/12/11
 * Time: 下午2:59
 */

namespace hospital\controllers;


use common\models\UserDoctor;
use common\models\UserNotice;

class NoticeController extends BaseController
{
    public function actionRecall($userid){

        $doctor=UserDoctor::findOne(['hospitalid'=>\Yii::$app->user->identity->hospitalid]);
        $notice=new UserNotice();
        $notice->userid=$doctor->userid;
        $notice->touserid=$userid;
        $args['doctor']=$doctor->name;
        $args['phone']=$doctor->phone;
        if($notice->send(1,$args)){
            \Yii::$app->getSession()->setFlash('success', '发送成功');
        }else{
            \Yii::$app->getSession()->setFlash('error', '失败');
        }
        return $this->redirect(\Yii::$app->request->getReferrer());
    }
}