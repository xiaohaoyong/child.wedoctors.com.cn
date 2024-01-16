<?php

namespace common\models;

use common\helpers\WechatSendTmp;
use Yii;

/**
 * This is the model class for table "examination".
 *
 */
class PublicHealth 
{

    public static $field=[
        1=>'姓名',
        12=>'生日'
    ];
    public static function inputData($row,$hospitalid)
    {
        $child=ChildInfo::find()
        //    ->leftJoin('user_login', '`user_login`.`userid` = `child_info`.`userid`')
        //     ->andWhere(['`user_login`.`phone`' => $rs[2]])
//                ->leftJoin('user_parent', '`user_parent`.`userid` = `pregnancy`.`familyid`')
//                ->andWhere(['user_parent.mother_id'=>$phone])
//                ->orWhere(['pregnancy.field4'=>$phone])
                //->leftJoin('user_parent', '`user_parent`.`userid` = `child_info`.`userid`')
                //->andWhere(['user_parent.mother'=>$rs[6]])
            ->andWhere(['doctor_parent.doctorid'=>$hospitalid])
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            //  ->andWhere(['doctor_parent.doctorid'=>190922])
            ->andWhere(['child_info.name'=>$row['field1']])
            ->andWhere(['child_info.birthday'=>strtotime($row['field12'])])
            ->one();
        echo $row['field1'];
        
        if($child){
            $child->is_ph=1;
            $child->save();
            echo "--公卫";
        }
        echo "\n";
        return true;
    }

}
