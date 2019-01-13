<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/23
 * Time: 上午10:46
 */

namespace api\modules\v2\controllers;


use common\models\ChildInfo;
use common\models\Interview;
use common\models\Pregnancy;

class ChildController extends \api\modules\v1\controllers\ChildController
{
    public function actionNewList(){
        $data = [];
        $gravida=[];
        if($this->userid) {
            $child = ChildInfo::find()->andFilterWhere(['userid' => $this->userid])->orderBy('birthday asc,id desc')->all();
            foreach ($child as $k => $v) {
                $row = $v->toArray();
                $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $v->birthday));
                $row['age'] = $DiffDate[0].'岁'.$DiffDate[1].'个月'.$DiffDate[2].'天';
                $data[] = $row;
            }

            $pregnancy = Pregnancy::find()->where(['familyid' => $this->userid])->orderBy('field11 desc')->one();
            if($pregnancy && $pregnancy->field49==0){
                $inter=Interview::findOne(['userid'=>$this->userid]);
                if(!$inter || !in_array($inter->pt_value,[1,2,3,8])){
                    $gravida['name']=$pregnancy->field1;
                    if($inter && $inter->prenatal==2) {
                        $field11='已分娩';
                    }elseif($pregnancy->weeks>38){
                        $field11='请完善追访信息';
                    }else{
                        $field11='孕'.$pregnancy->weeks."周";

                    }
                    $gravida['field11'] =$field11;

                }

            }
        }

        return ['list'=>$data,'gravida'=>$gravida];
    }
}