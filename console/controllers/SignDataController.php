<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/6/2
 * Time: 上午9:47
 */

namespace console\controllers;


use common\models\Appoint;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\HospitalForm;
use common\models\Pregnancy;
use common\models\UserDoctor;

class SignDataController extends \yii\base\Controller
{
    /**
     * 每日结算社区工作情况
     */
    function actionDay(){
        $satime = strtotime('-1 day',strtotime(date('Y-m-d')));
        $doctors = UserDoctor::find()->where(['county'=>1106])->all();
        foreach ($doctors as $k => $v) {
            echo $v->name;
            for ($stime = $satime; $stime < time(); $stime = strtotime('+1 day', $stime)) {
                echo date('Ymd',$stime);
                $etime = strtotime('+1 day', $stime);
                $doctorParent = DoctorParent::find()->where(['doctorid' => $v->userid])
                    ->andWhere(['>=', 'createtime', $stime])
                    ->andWhere(['<', 'createtime', $etime])
                    ->count();
                $child_info1=ChildInfo::find()
                    ->leftJoin('doctor_parent','doctor_parent.parentid=child_info.userid')
                    ->andWhere(['doctor_parent.doctorid'=>$v->userid])
                    ->leftJoin('autograph','autograph.userid=child_info.userid')
                    ->andWhere(['>=', 'autograph.createtime', $stime])
                    ->andWhere(['<', 'autograph.createtime', $etime])
                    ->andWhere(['>=', 'child_info.birthday', strtotime('-6 year',$stime)])
                    ->andWhere('autograph.createtime>=child_info.createtime')
                    ->count();
                $child_info2=ChildInfo::find()
                    ->leftJoin('doctor_parent','doctor_parent.parentid=child_info.userid')
                    ->andWhere(['doctor_parent.doctorid'=>$v->userid])
                    ->leftJoin('autograph','autograph.userid=child_info.userid')
                    ->andWhere(['>=', 'child_info.createtime', $stime])
                    ->andWhere(['<', 'child_info.createtime', $etime])
                    ->andWhere(['>=', 'child_info.birthday', strtotime('-6 year',$stime)])
                    ->andWhere('autograph.createtime<child_info.createtime')
                    ->count();

                $pregLCount=Pregnancy::find()
                    ->andWhere(['>','pregnancy.field16',strtotime('-43 week',$stime)])
                    //->leftJoin('autograph', '`autograph`.`userid` = `pregnancy`.`familyid`')
                    ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `pregnancy`.`familyid`')
                    ->andWhere(['>=', 'doctor_parent.createtime', $stime])
                    ->andWhere(['<', 'doctor_parent.createtime', $etime])
                    ->andFilterWhere(['`doctor_parent`.`doctorid`' => $v->userid])->count();


                $appoint = Appoint::find()->where(['doctorid' => $v->userid])
                    ->andWhere(['appoint_date' => $stime])
                    ->andWhere(['!=', 'state', 3])
                    ->count();
                $rs = [];
                $rs['sign1'] = $doctorParent;
                $rs['sign2'] = $child_info1+$child_info2;
                $rs['sign3'] = $pregLCount;

                $rs['appoint_num'] = $appoint;
                $rs['doctorid'] = $v->userid;
                $rs['date'] = $stime;
                var_dump($rs);
                $hospitalFrom = HospitalForm::find()->where(['doctorid' => $v->userid])->andWhere(['date' => $stime])->one();
                $hospitalFrom = $hospitalFrom ? $hospitalFrom : new HospitalForm();
                $hospitalFrom->load(['HospitalForm' => $rs]);
                $hospitalFrom->save();
                var_dump($hospitalFrom->firstErrors);
            }
        }
    }

}