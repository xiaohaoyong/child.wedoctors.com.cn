<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/7/9
 * Time: 上午10:34
 */

namespace docapi\modules\v1\controllers;


use common\models\ChildInfo;
use common\models\Doctors;
use common\models\Examination;
use common\models\UserParent;
use docapi\controllers\Controller;
use hospital\models\user\ChildInfoSearchModel;

class ChildrenController extends Controller
{
    public function actionIndex(){
        $params=\Yii::$app->request->queryParams;
        $searchModel = new ChildInfoSearchModel();
        $doctor = Doctors::findOne(['userid' => $this->userid]);
        $searchModel->admin=$doctor->hospitalid;
        if(!$params)
        {
            $params['DoctorParentSearchModel']['createtime']=strtotime(date('-30 day'));
        }
        $list=[];
        $dataProvider = $searchModel->search($params);
        $dataProvider->query->select('name,userid,id');
        foreach ($dataProvider->getModels() as $k => $v) {
            $row=$v->toArray();
            $row['phone']=UserParent::findOne(['userid'=>$v->userid])->getPhone();
            $list[] = $row;
        }
        $pageTotal=ceil($dataProvider->query->count()/20);

        return ['list'=>$list,'pageTotal'=>$pageTotal];
    }
    public function actionView($id){

        $child=ChildInfo::findOne(['id'=>$id]);
        $parent=$child->parent;
        $row=$child->toArray()+$parent->toArray();
        $row['birthday']=date('Y-m-d',$row['birthday']);

        $ex=Examination::find()->andFilterWhere(['childid'=>$id])->orderBy('field2 desc,field3 desc')->all();
        foreach($ex as $k=>$v)
        {
            $row['id']=$v->id;
            $row['tizhong']=$v->field70;
            $row['shenchang']=$v->field40;
            $row['touwei']=$v->field80;
            $row['bmi']=$v->field20;
            $row['feipang']=$v->field53;
            $row['fayu']=$v->field35;
            $row['yingyang']=$v->field15;
            $row['title']=$v->field2."岁".$v->field3."月体检";
            $row['next']=$v->field52;
            $row['date']=$v->field4;

            $data[]=$row;
        }
        return ['list'=>$data,'row'=>$row];

    }

}