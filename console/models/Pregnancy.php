<?php

namespace console\models;

/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/6/13
 * Time: 下午3:23
 */
use common\components\Log;
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
        $value['field2']=strtotime($value['field2']);
        $value['field11']=strtotime($value['field11']);
        $value['field15']=strtotime($value['field15']);
        $value['field16']=strtotime($value['field16']);
        $value['field49']=$value['field49']=='是'?1:0;
        $value['field61']=strtotime($value['field61']);
        $value['field70']=floor($value['field70']);
        $field74=array_search($value['field74'],\common\models\Pregnancy::$bmi);
        $value['field74']=$field74?$field74:0;
        $value['source']=$hospital;
        $preg->load(['Pregnancy'=>$value]);
        if(!$preg->save()){
            var_dump($preg->firstErrors);
            var_dump($value);exit;
        }
        var_dump($preg->id);
    }
}