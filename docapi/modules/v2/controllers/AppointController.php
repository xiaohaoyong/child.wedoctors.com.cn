<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/6/14
 * Time: 下午2:00
 */

namespace docapi\modules\v2\controllers;


use common\models\Appoint;
use common\models\ChildInfo;
use common\models\Doctors;
use common\models\Hospital;
use common\models\UserDoctor;
use common\models\UserParent;
use docapi\models\AppointSearch;

class AppointController extends \docapi\controllers\AppointController
{
    public function actionView($id)
    {
        $doctor = Doctors::findOne(['userid' => $this->userid]);
        $userDoctor = UserDoctor::findOne(['hospitalid' => $doctor->hospitalid]);
        $time=strtotime(date('Ymd'));
        $appoint=Appoint::findOne(['id'=>$id,'doctorid'=>$userDoctor->userid]);
        $row=[];
        if ($appoint) {
            $appoint->state = 2;
            $appoint->save();
            $row = $appoint->toArray();
            $doctor = UserDoctor::findOne(['userid' => $appoint->doctorid]);
            if ($doctor) {
                $hospital = Hospital::findOne($doctor->hospitalid);
            }
            $row['hospital'] = $hospital->name;
            $row['type'] = Appoint::$typeText[$appoint->type];
            $row['time'] = date('Y.m.d', $appoint->appoint_date) . "  " . Appoint::$timeText[$appoint->appoint_time];
            $row['duan'] = $appoint->appoint_time;
            $row['id'] = $appoint->id;
            $row['phone'] = $appoint->phone;
            $index = Appoint::find()
                ->andWhere(['appoint_date' => $appoint->appoint_date])
                ->andWhere(['<', 'id', $id])
                ->andWhere(['doctorid' => $appoint->doctorid])
                ->andWhere(['appoint_time' => $appoint->appoint_time])
                ->andWhere(['type' => $appoint->type])
                ->count();
            $row['index'] = $index + 1;


            if ($appoint->type == 4 || $appoint->type == 7) {
                $AppointAdult = \common\models\AppointAdult::findOne(['userid' => $appoint->userid]);
                $row['child_name'] = $AppointAdult->name;

                $r['field']='姓名';
                $r['value']=$AppointAdult->name;
                $rs[]=$r;
                $r['field']='性别';
                $r['value']=\common\models\AppointAdult::$genderText[$AppointAdult->gender];
                $rs[]=$r;
                $r['field']='手机';
                $r['value']=$AppointAdult->phone;
                $r['field']='证件号';
                $r['value']=$AppointAdult->id_card;
                $rs[]=$r;

            } elseif ($appoint->type == 5 || $appoint->type == 6) {
                $preg = \common\models\Pregnancy::findOne(['id' => $appoint->childid]);
                $row['child_name'] = $preg->field1;

                $r['field']='末次月经';
                $r['value']=date('Ymd', $preg->field11);
                $rs[]=$r;
                $r['field']='证件号';
                $r['value']=$preg->field4;
                $rs[]=$r;
                $r['field']='户籍地';
                $r['value']=\common\models\Pregnancy::$field90[$preg->field90];
                $rs[]=$r;
                $r['field']='孕妇户籍地';
                $r['value']=\common\models\Area::$all[$preg->field7];
                $rs[]=$r;
                $r['field']='丈夫户籍地';
                $r['value']=\common\models\Area::$all[$preg->field39];
                $rs[]=$r;
                $r['field']='现住址';
                $r['value']=$preg->field10;
                $rs[]=$r;
            } else {
                $child = \common\models\ChildInfo::findOne(['id' => $appoint->childid]);
                $row['child_name'] = ChildInfo::findOne($appoint->childid)->name;

                $parent = \common\models\UserParent::findOne(['userid' => $appoint->userid]);

                $r['field']='姓名';
                $r['value']=$child->name;
                $rs[]=$r;
                $r['field']='生日';
                $r['value']=date('Ymd', $child->birthday);
                $rs[]=$r;
                $r['field']='儿童户籍';
                $r['value']=$child->fieldu47;
                $rs[]=$r;
                $r['field']='母亲姓名';
                $r['value']=$parent->mother;
                $rs[]=$r;
                $r['field']='户籍地';
                $r['value']=$parent->field44;
                $rs[]=$r;
            }

        }
        return ['appoint' => $row, 'child' => [],'row'=>$rs];
    }

    public function actionList()
    {
        $doctor = Doctors::findOne(['userid' => $this->userid]);
        $userDoctor = UserDoctor::findOne(['hospitalid' => $doctor->hospitalid]);

        $params = \Yii::$app->request->queryParams;
        $searchModel = new AppointSearch();
        if($params['AppointSearch']['appoint_date']){
            $params['AppointSearch']['appoint_date'] = strtotime($params['AppointSearch']['appoint_date']);
        }

        $dataProvider = $searchModel->search($params);
        $query = $dataProvider->query;
        // grid filtering conditions
        $dataProvider->query->andFilterWhere(['doctorid' => $userDoctor->userid]);
        //$dataProvider->query->andFilterWhere(['in','type' => [1,2]]);

        $dataProvider->query->orderBy('appoint_date asc,appoint_time asc,id asc');
        //echo $dataProvider->query->createCommand()->getRawSql();exit;
        foreach ($dataProvider->getModels() as $k => $v) {
            $rs = $v;

            if($v->type==4||$v->type==7){
                $AppointAdult=\common\models\AppointAdult::findOne(['userid' => $v->userid]);
                $row['child_name'] = $AppointAdult->name;

            }elseif($v->type==5 || $v->type==6){
                $preg=\common\models\Pregnancy::findOne(['id' => $v->childid]);
                $row['child_name'] = $preg->field1;
            }else{
                $child= \common\models\ChildInfo::findOne(['id' => $v->childid]);
                $row['child_name'] = $child->name;

            }
            $row['duan'] = $rs->appoint_time;
            $row['id'] = $rs->id;
            $row['state'] = Appoint::$stateText[$rs->state];
            $row['mode'] =$rs->mode;
            $row['modeText']=Appoint::$modeText[$rs->mode];

            $index = Appoint::find()
                ->andWhere(['appoint_date' => $rs->appoint_date])
                ->andWhere(['<', 'id', $rs->id])
                ->andWhere(['doctorid' => $rs->doctorid])
                ->andWhere(['appoint_time' => $rs->appoint_time])
                ->andWhere(['type' => $rs->type])
                ->count();
            $row['index'] = $index + 1;
            $row['type'] = Appoint::$typeText[$rs->type];
            $row['appoint_time'] = date('Y年m月d', $rs->appoint_date);

            $list[] = $row;
        }

        $count = $query->count();
        $pageTotal = ceil($count/20);

        $states=Appoint::$stateText;
        $types=Appoint::$typeText;

        return ['list' => $list, 'pageTotal'=>$pageTotal,'count' => $count,'types'=>$types,'states'=>$states];
    }

}