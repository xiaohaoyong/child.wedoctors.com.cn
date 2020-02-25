<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/6/14
 * Time: 下午2:00
 */

namespace docapi\modules\v1\controllers;


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
        $appoint = Appoint::findOne(['id' => $id]);

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
            $row['child_name'] = ChildInfo::findOne($appoint->childid)->name;
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
        }
        $child = ChildInfo::findOne(['id' => $appoint->childid]);
        if ($child) {
            $userParent = UserParent::findOne(['userid' => $child->userid]);
            $rs['sex'] = $child->gender == 1 ? '男' : '女';
            $rs['birthday'] = date('Y年m月d日', $child->birthday);
            $rs['mother'] = $userParent->mother;
            $rs['huji'] = $userParent->field44;
        }

        return ['appoint' => $row, 'child' => $rs];
    }

    public function actionList()
    {
        $doctor = Doctors::findOne(['userid' => $this->userid]);
        $userDoctor = UserDoctor::findOne(['hospitalid' => $doctor->hospitalid]);

        $params = \Yii::$app->request->queryParams;
        $searchModel = new AppointSearch();
        if (!$params['AppointSearch']['phone']) {
            $params['AppointSearch']['phone'] = '';
        }
        if (!$params['AppointSearch']['type']) {
            $params['AppointSearch']['type'] = '';
        }
        if (!$params['AppointSearch']['appoint_date']) {
            $params['AppointSearch']['appoint_date'] = '';
        }

        $dataProvider = $searchModel->search($params);
        $query = $dataProvider->query;
        // grid filtering conditions
        $dataProvider->query->andFilterWhere(['doctorid' => $userDoctor->userid]);
        $dataProvider->query->orderBy('appoint_date asc,appoint_time asc,id asc');
``        //echo $dataProvider->query->createCommand()->getRawSql();
        foreach ($dataProvider->getModels() as $k => $v) {
            $rs = $v;
            $child = ChildInfo::findOne(['id' => $rs->childid]);
            $row['child_name'] = $child->name;
            $row['duan'] = $rs->appoint_time;
            $row['id'] = $rs->id;

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

        $count = $query->andFilterWhere(['appoint_date' => strtotime(date('Y-m-d'))])->count();

        return ['list' => $list, 'count' => $count];
    }

}