<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace frontend\controllers;

use common\models\Appoint;
use common\models\DoctorParent;
use common\models\Hospital;
use common\models\UserDoctor;
use common\models\UserDoctorAppoint;
use dosamigos\qrcode\QrCode;
use yii\web\Controller;
use yii\web\Response;

class DoctorsController extends Controller
{
    public function actionIndex($search='',$county=0){
        $query=UserDoctor::find();
        if($search){
            $query->andFilterWhere(['like','name',$search]);
        }
        if($search || $county){

            if($county){
                $query->andWhere(['county'=>$county]);
            }


            $doctors=$query->orderBy('appoint desc')->all();
        }else{
            $doctors=$query->orderBy('appoint desc')->limit(10)->all();
        }

        $docs=[];

        foreach($doctors as $k=>$v){
            $rs=$v->toArray();
            $rs['name']=Hospital::findOne($v->hospitalid)->name;
            $docs[]=$rs;
        }



        return $this->renderPartial('list', [
            'doctors'=>$docs,
            'county'=>$county
        ]);
    }
    public function actionQr($text){

        QrCode::png($text);
    }
}