<?php
/**
 * Created by PhpStorm.
 */

namespace api\modules\v4\controllers;

use api\controllers\Controller;
use common\components\Code;
use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\AppointAdult;
use common\models\AppointComment;
use dosamigos\qrcode\lib\Enum;
use dosamigos\qrcode\QrCode;
use linslin\yii2\curl\Curl;
use yii\data\Pagination;

class ApCommentController extends Controller {

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
            $tot = $is_envir+$is_process+$is_staff;
            if($tot>0 && $tot<=6) {
                $acomment->is_rate = 3;
            }elseif($tot>=7 && $tot<=10){
                $acomment->is_rate = 2;
            }elseif($tot>=11){
                $acomment->is_rate = 1;
            }
            $acomment->save(false);

            $msg = '评价成功';
            return new Code(10000,$msg);
        }

    }

    public function actionView()
    {
        $id=\Yii::$app->request->get('id');
        if(empty($id)){
            $msg='参数异常';
            return new Code(20000,$msg);
        }
        $appoint = Appoint::findOne(['id' => $id]);
        if(empty($appoint)){
            $msg='无此预约，请确认';
            return new Code(20000,$msg);
        }
        $appointcomment = AppointComment::findOne(['aid' => $id]);
        if($appointcomment){
            $all = $appointcomment->is_envir+$appointcomment->is_process+$appointcomment->is_staff;
            if($all<=6){
                $return['all']='差评';
            }elseif($all>=7 && $all<=10){
                $return['all']='中评';
            }elseif($all>10){
                $return['all']='好评';
            }
            $return['is_envir']=$appointcomment->is_envir;
            $return['is_process']=$appointcomment->is_process;
            $return['is_staff']=$appointcomment->is_staff;
            return new Code(10000,$return);
        }else{
            $msg='暂无评价';
            return new Code(20000,$msg);
        }

    }




}