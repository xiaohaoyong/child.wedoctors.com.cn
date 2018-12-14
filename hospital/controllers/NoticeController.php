<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/12/11
 * Time: 下午2:59
 */

namespace hospital\controllers;


use common\models\ChildInfo;
use common\models\UserDoctor;
use common\models\UserNotice;
use common\models\UserParent;

class NoticeController extends BaseController
{
    public function actionRecall($userid,$childid){

        $session = \Yii::$app->session;
        if(!$referrer_url=$session->get('referrer_url')) {
            $session->set('referrer_url', \Yii::$app->request->getReferrer());
        }

        if(\Yii::$app->request->post()) {
            $doctor = UserDoctor::findOne(['hospitalid' => \Yii::$app->user->identity->hospitalid]);
            $notice = new UserNotice();
            $notice->load(\Yii::$app->request->post());
            $notice->userid = $doctor->userid;
            $notice->touserid = $userid;
            $args['doctor'] = $doctor->name;
            if(strlen($doctor->phone)==8){
                $args['phone'] = '010 '.$doctor->phone;
            }else{
                $args['phone'] = $doctor->phone;
            }
            if ($notice->send(1, $args)) {
                \Yii::$app->getSession()->setFlash('success', '发送成功');
            } else {
                \Yii::$app->getSession()->setFlash('error', '失败');
            }
            $session->remove('referrer_url');
            return $this->redirect($referrer_url);
        }
        $userParent=UserParent::findOne(['userid'=>$userid]);
        $child=ChildInfo::findOne($childid);
        $userNotice=new UserNotice();
        return $this->render('recall', [
            'userParent'=>$userParent,
            'child'=>$child,
            'notice'=>$userNotice,
        ]);
    }
}