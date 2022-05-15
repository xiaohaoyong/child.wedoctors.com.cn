<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2022/5/7
 * Time: 3:00 PM
 */

namespace api\modules\v4\controllers;


use api\controllers\Controller;
use common\models\Access;

class DataController extends Controller
{
    public function actionAppointView($id){

        $access=new Access();
        $access->userid=$this->userid;
        $access->cid=$id;
        $access->long=0;
        $access->type=1;
        $access->save();
    }
    public function actionArticleView($id,$long){

        $access=new Access();
        $access->userid=$this->userid;
        $access->cid=$id;
        $access->long=round($long/1000);
        $access->type=2;
        $access->save();
    }
    public function actionVideoView($id,$long){

        $access=new Access();
        $access->userid=$this->userid;
        $access->cid=$id;
        $access->long=round($long/1000);
        $access->type=3;
        $access->save();
    }
    public function actionVaccine($id){

        $access=new Access();
        $access->userid=$this->userid;
        $access->cid=$id;
        $access->long=0;
        $access->type=4;
        $access->save();
    }

}