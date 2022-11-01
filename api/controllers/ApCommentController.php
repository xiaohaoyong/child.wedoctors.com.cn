<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace api\controllers;

use common\components\Code;
use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\AppointAdult;
use common\models\AppointComment;
use dosamigos\qrcode\lib\Enum;
use dosamigos\qrcode\QrCode;
use linslin\yii2\curl\Curl;
use yii\data\Pagination;

class ApcommentController extends Controller {

    /**
     * 预约就诊评价
     * @param $id
     */
    public function actionIndex(){
        $aid=\Yii::$app->request->get('id');
        $is_envir=\Yii::$app->request->get('is_envir');
        $is_envir_on=\Yii::$app->request->get('is_envir_on');
        $is_process=\Yii::$app->request->get('is_process');
        $is_process_on=\Yii::$app->request->get('is_process_on');
        $is_staff=\Yii::$app->request->get('is_staff');
        $is_staff_on=\Yii::$app->request->get('is_staff_on');
        if(empty($aid) || empty($is_envir) || empty($is_process) || empty($is_staff)){
            $msg = '参数有误';
            return new Code(20000,$msg);
        }
        $appoint = Appoint::find()->where(['id'=>$aid])->one();
        if(empty($appoint)){
            $msg='无此预约，请确认';
            return new Code(20000,$msg);
        }
        $appointcomment=AppointComment::find()->where(['aid'=>$aid])->one();
        if($appointcomment){
            $msg='已经评价过了';
            return new Code(20000,$msg);
        }else{
            $acomment = new AppointComment();
            $acomment->aid = $aid;
            $acomment->userid=$appoint->userid;
            $acomment->doctorid=$appoint->doctorid;
            $acomment->createtime = time();
            $acomment->is_envir=$is_envir;
            $acomment->is_envir_on=$is_envir_on;
            $acomment->is_process=$is_process;
            $acomment->is_process_on=$is_process_on;
            $acomment->is_staff=$is_staff;
            $acomment->is_staff_on=$is_staff_on;
            $acomment->save(false);

            $msg = '评价成功';
            return new Code(10000,$msg);
        }

    }

    public function actionView()
    {
        $id=\Yii::$app->request->get('id');
        $appoint = Appoint::findOne(['id' => $id]);
        $appointcomment = AppointComment::findOne(['aid' => $id]);



        return $appoint;
    }




}