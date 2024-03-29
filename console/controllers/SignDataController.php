<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/6/2
 * Time: 上午9:47
 */

namespace console\controllers;


use common\models\Appoint;
use common\models\Autograph;
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
    function actionDay()
    {
        $satime = strtotime('-1 day', strtotime(date('Y-m-d')));
        $doctors = UserDoctor::find()->all();
        foreach ($doctors as $k => $v) {
            echo $v->name;
            for ($stime = $satime; $stime < time(); $stime = strtotime('+1 day', $stime)) {
                echo date('Ymd', $stime);
                $etime = strtotime('+1 day', $stime);
                $doctorParent = DoctorParent::find()->where(['doctorid' => $v->userid])
                    ->andWhere(['>=', 'createtime', $stime])
                    ->andWhere(['<', 'createtime', $etime])
                    ->count();
                $child_info1 = ChildInfo::find()
                    ->leftJoin('doctor_parent', 'doctor_parent.parentid=child_info.userid')
                    ->andWhere(['doctor_parent.doctorid' => $v->userid])
                    ->leftJoin('autograph', 'autograph.userid=child_info.userid')
                    ->andWhere(['>=', 'autograph.createtime', $stime])
                    ->andWhere(['<', 'autograph.createtime', $etime])
                    ->andWhere(['>=', 'child_info.birthday', strtotime('-6 year', $stime)])
                    ->andWhere('autograph.createtime>=child_info.createtime')
                    ->count();
                $child_info2 = ChildInfo::find()
                    ->leftJoin('doctor_parent', 'doctor_parent.parentid=child_info.userid')
                    ->andWhere(['doctor_parent.doctorid' => $v->userid])
                    ->leftJoin('autograph', 'autograph.userid=child_info.userid')
                    ->andWhere(['>=', 'child_info.createtime', $stime])
                    ->andWhere(['<', 'child_info.createtime', $etime])
                    ->andWhere(['>=', 'child_info.birthday', strtotime('-6 year', $stime)])
                    ->andWhere('autograph.createtime<child_info.createtime')
                    ->count();

                $pregLCount = Pregnancy::find()
                    ->andWhere(['pregnancy.field49' => 0])
                    ->andWhere(['or',['>', 'pregnancy.field16', strtotime('-43 week', $stime)],['>', 'pregnancy.field11', strtotime('-43 week', $stime)]])
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
                $rs['sign2'] = $child_info1 + $child_info2;
                $rs['sign3'] = $pregLCount;

                $rs['appoint_num'] = $appoint;
                $rs['doctorid'] = $v->userid;
                $rs['date'] = $stime;
                $hospitalFrom = HospitalForm::find()->where(['doctorid' => $v->userid])->andWhere(['date' => $stime])->one();
                $hospitalFrom = $hospitalFrom ? $hospitalFrom : new HospitalForm();
                $hospitalFrom->load(['HospitalForm' => $rs]);
                $hospitalFrom->save();
                echo "\n";
            }
        }
    }

    function actionA()
    {
        $stime = strtotime('2019-01-01');
        $etime = strtotime('2019-12-31');
        $doctors = UserDoctor::find()->where(['county'=>1106])->all();
        foreach ($doctors as $k => $v) {
            $doctorParent = DoctorParent::find()->where(['doctorid' => $v->userid])
                ->andWhere(['>=', 'createtime', $stime])
                ->andWhere(['<', 'createtime', $etime])
                ->count();

            //签约儿童总数
            $child_info1=ChildInfo::find()
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                ->andWhere(['>=', 'doctor_parent.createtime', $stime])
                ->andWhere(['<', 'doctor_parent.createtime', $etime])
                ->andFilterWhere(['`doctor_parent`.`doctorid`' =>  $v->userid])
                ->andFilterWhere(['>', '`child_info`.birthday', strtotime('2016-01-01')])
                ->count();

            //签约儿童总数
            $child_info2=ChildInfo::find()
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                ->andWhere(['>=', 'doctor_parent.createtime', $stime])
                ->andWhere(['<', 'doctor_parent.createtime', $etime])
                ->andFilterWhere(['`doctor_parent`.`doctorid`' =>  $v->userid])
                ->andFilterWhere(['>', '`child_info`.birthday', strtotime('2013-01-01')])
                ->count();

            $pregLCount = Pregnancy::find()
                ->andWhere(['pregnancy.field49' => 0])
                ->andWhere(['>', 'pregnancy.field16', strtotime('-43 week', $stime)])
                //->leftJoin('autograph', '`autograph`.`userid` = `pregnancy`.`familyid`')
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `pregnancy`.`familyid`')
                ->andWhere(['>=', 'doctor_parent.createtime', $stime])
                ->andWhere(['<', 'doctor_parent.createtime', $etime])
                ->andFilterWhere(['`doctor_parent`.`doctorid`' => $v->userid])->count();


            $appoint = Appoint::find()->where(['doctorid' => $v->userid])
                ->andWhere(['>','appoint_date' , $stime])
                ->andWhere(['<','appoint_date' , $etime])
                ->andWhere(['!=', 'state', 3])
                ->count();
            $rs = [];
            $rs['name']=$v->name;
            $rs['sign1'] = $doctorParent;
            $rs['sign2'] = $child_info1;
            $rs['sign3'] = $child_info2;
            $rs['sign4'] = $pregLCount;
            $rs['appoint_num'] = $appoint;
            echo implode(',',$rs);
            echo "\n";
        }
    }

    function actionDayg()
    {
        $satime = strtotime('-30 day', strtotime(date('Y-m-d')));
        $doctors = UserDoctor::find()->where(['userid' => 216593])->all();
        foreach ($doctors as $k => $v) {
            echo $v->name;
            for ($stime = $satime; $stime < time(); $stime = strtotime('+1 day', $stime)) {
                echo date('Ymd', $stime);
                $etime = strtotime('+1 day', $stime);

                $appoint = Appoint::find()->where(['doctorid' => $v->userid])
                    ->andWhere(['appoint_date' => $stime])
                    ->andWhere(['!=', 'state', 3])
                    ->count();
                $rs = [];

                $rs['appoint_num'] = $appoint;
                $rs['doctorid'] = $v->userid;
                $rs['date'] = $stime;
                $hospitalFrom = HospitalForm::find()->where(['doctorid' => $v->userid])->andWhere(['date' => $stime])->one();
                $hospitalFrom = $hospitalFrom ? $hospitalFrom : new HospitalForm();
                $hospitalFrom->load(['HospitalForm' => $rs]);
                $hospitalFrom->save();
                echo "\n";
            }
        }
    }
}