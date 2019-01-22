<?php

namespace console\models;

/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/6/13
 * Time: 下午3:23
 */
use common\components\Log;
use common\models\Area;
use common\models\ChildInfo;
use common\models\User;
use common\models\UserLogin;
use common\models\UserParent;

class Pregnancy
{
    /**
     * 主操作函数
     * @param $value
     */
    public function inputData($value,$hospital)
    {
        $preg=\common\models\Pregnancy::findOne(['field0'=>$value['field0']]);
        $preg=$preg?$preg:new \common\models\Pregnancy();
        $parent=UserParent::find()
            ->where(['mother_id'=>$value['field4']])
            ->orWhere(['mother_phone'=>$value['field6']])
            ->orWhere(['father_phone'=>$value['field38']])
            ->orWhere(['father_phone'=>$value['field6']])
            ->orWhere(['mother_phone'=>$value['field38']])
            ->one();
        $value['familyid']=$parent?$parent->userid:0;
        $value['field2']=$value['field2']?strtotime(substr($value['field2'],0,10)):0;
        $value['field5']=$value['field5']?strtotime(substr($value['field5'],0,10)):0;
        $field7=array_search($value['field7'],Area::$province);
        $value['field7']=$field7?$field7:0;
        $value['field11']=$value['field11']?strtotime(substr($value['field11'],0,10)):0;
        $value['field15']=$value['field15']?strtotime(substr($value['field15'],0,10)):0;
        $value['field16']=$value['field16']?strtotime(substr($value['field16'],0,10)):0;
        $value['field49']=$value['field49']=='是'?1:0;
        $value['field89']=$value['field89']=='是'?1:0;

        $value['field61']=$value['field61']?strtotime(substr($value['field61'],0,10)):0;
        $value['field70']=floor($value['field70']);


        $value['field62']=$value['field62']?$value['field62']:0;

        $field8=array_search($value['field8'],Area::$province);
        $value['field8']=$field8?$field8:0;

        $field9=array_search($value['field9'],Area::$province);
        $value['field9']=$field9?$field9:0;


        $field39=array_search($value['field39'],Area::$province);
        $value['field39']=$field39?$field39:0;

        $field74=array_search($value['field74'],\common\models\Pregnancy::$bmi);
        $value['field74']=$field74?$field74:0;

        $field81=array_search($value['field81'],\common\models\Pregnancy::$field81);
        $value['field81']=$field81?$field81:0;

        $value['source']=$hospital;
        $value['doctorid']=$hospital;
        $preg->load(['Pregnancy'=>$value]);

        if(!$preg->save()){
            var_dump($preg->firstErrors);
            var_dump($value);exit;
        }
        var_dump($preg->id);
    }
}