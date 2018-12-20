<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/12/6
 * Time: 下午4:11
 */

namespace hospital\controllers;


use hospital\models\Data;

class DataController extends BaseController
{
    public function actionUpload(){
        $data=new Data();
        return $this->render('upload',[
            'data'=>$data,
        ]);
    }

}