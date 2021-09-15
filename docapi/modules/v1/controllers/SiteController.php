<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/6/13
 * Time: 上午10:37
 */

namespace docapi\modules\v1\controllers;


use common\models\ChildInfo;
use common\models\Doctors;
use common\models\UserDoctor;
use docapi\controllers\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
//今日签约数
        $doctor = Doctors::findOne(['userid' => $this->userid]);
        $userDoctor = UserDoctor::findOne(['hospitalid' => $doctor->hospitalid]);
        $today=strtotime(date('Y-m-d 00:00:00'));

        if ($userDoctor) {
            $data['todayNum'] = ChildInfo::find()
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                ->andFilterWhere(['`doctor_parent`.`doctorid`' => $userDoctor->userid])
                ->andFilterWhere(['child_info.admin' => $userDoctor->hospitalid])
                ->andFilterWhere([">", '`doctor_parent`.createtime', $today])
                ->andFilterWhere(['>', 'child_info.birthday', strtotime('-3 year')])
                ->count();
            //签约儿童总数
            $data['todayNumTotal'] = ChildInfo::find()
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                ->andFilterWhere(['`doctor_parent`.`doctorid`' => $userDoctor->userid])
                ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-3 year')])
                ->andFilterWhere(['child_info.admin' => $userDoctor->hospitalid])
                ->count();
            $data['hospital']=$userDoctor->name;
        }
        return $data;
    }
    public function actionData(){
        $doctor = Doctors::findOne(['userid' => $this->userid]);
        $userDoctor = UserDoctor::findOne(['hospitalid' => $doctor->hospitalid]);
        $today=strtotime(date('Y-m-d 00:00:00'));
        //签约儿童总数
        $data['todayNumTotal']=ChildInfo::find()
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $userDoctor->userid])
            ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-3 year')])
            ->andFilterWhere(['child_info.admin'=>$userDoctor->hospitalid])
            ->count();


        //管辖儿童数（0-3）
        $data['childNum']=ChildInfo::find()
            ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
            ->andFilterWhere(['`child_info`.`source`' => $userDoctor->hospitalid])
            ->andFilterWhere(['`child_info`.admin'=>$userDoctor->hospitalid])
            ->count();
        //今日签约数
        $data['todayNum'] = ChildInfo::find()
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $userDoctor->userid])
            ->andFilterWhere(['child_info.admin' => $userDoctor->hospitalid])
            ->andFilterWhere([">", '`doctor_parent`.createtime', $today])
            ->andFilterWhere(['>', 'child_info.birthday', strtotime('-3 year')])
            ->count();
        //签约率
        if($data['childNum']) {
            $data['baifen'] = round(($data['todayNumTotal'] / $data['childNum']) * 100,1);
        }else{
            $data['baifen'] = 0;
        }
        return $data;
    }

}