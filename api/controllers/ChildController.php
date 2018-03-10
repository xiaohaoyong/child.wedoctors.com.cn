<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/23
 * Time: 上午10:46
 */

namespace api\controllers;


use common\components\Code;
use common\models\ChildInfo;
use common\models\Examination;

class ChildController extends Controller
{
    public function actionList(){
        $data = [];
        if($this->userid) {
            $child = ChildInfo::find()->andFilterWhere(['userid' => $this->userid])->orderBy('birthday asc,id desc')->all();
            foreach ($child as $k => $v) {
                $row = $v->toArray();
                $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $v->birthday));
                $row['age'] = $DiffDate[0].'岁'.$DiffDate[1].'个月'.$DiffDate[2].'天';
                $data[] = $row;
            }
        }
        return $data;
    }
    public function actionAdd(){

        $name=\Yii::$app->request->post('name');
        $date=\Yii::$app->request->post('date');
        $sex=\Yii::$app->request->post('sex');
        $id=\Yii::$app->request->post('id');

        $child=ChildInfo::findOne($id);
        $child=$child?$child:new ChildInfo();
        $child->userid=$this->userid;
        $child->name=$name;
        $child->gender=$sex=='男'?1:2;
        $child->birthday=strtotime($date);
        $child->save();
        if($child->errors){
            return new Code(20010,'失败');
        }
    }
    public function actionView($id){
        $child=ChildInfo::findOne($id);
        if($child){
            $row=$child->toArray();
            $row['birthday']=date('Y-m-d',$child->birthday);
            $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $child->birthday));
            $row['age'] = $DiffDate[0].'岁'.$DiffDate[1].'个月'.$DiffDate[2].'天';
            return $row;
        }else{
            return new Code(20010,'失败');
        }
    }
    public function actionChildEx($id){
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
        return $data;
    }
    public function actionChildExView($id){
        $ex=Examination::findOne($id);
        $exa=new Examination();
        $field=$exa->attributeLabels();
        if($ex) {
            $list = $ex->toArray();
            unset($list['id']);
            unset($list['childid']);
            unset($list['source']);

            foreach ($list as $k => $v){
                $row['name']=$field[$k];
                $row['value']=$v;
                $data[$k]=$row;
            }
        }
        return $data;
    }
}