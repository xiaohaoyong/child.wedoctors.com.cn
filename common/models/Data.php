<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/12/20
 * Time: 下午4:27
 */

namespace common\models;


class Data
{
    public static function ChildTotal($doctorid=0,$hospitalid=0,$sign=0,$admin=0,$age=0,$day=0){
        //签约儿童总数
        $query=ChildInfo::find();
        if($sign){
            $query ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid]);
        }
        if($age==1){
            $query->andFilterWhere(['>', '`child_info`.birthday', strtotime('-3 year')]);
        }elseif($age==2){
            $query->andFilterWhere(['>', '`child_info`.birthday', strtotime('-6 year')]);
        }
        if($day){
            $query->andFilterWhere([">",'`doctor_parent`.createtime',$day]);
        }
        if($admin){
            $query->andFilterWhere(['child_info.admin'=>$hospitalid]);
        }

        return $query->count();
    }
}