<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/2/28
 * Time: 下午1:38
 */

namespace backend\models;


use common\components\HttpRequest;
use common\helpers\WechatSendTmp;
use common\models\Chain;
use common\models\DoctorParent;
use common\models\Notice;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\UserParent;
use yii\base\Model;

class Push extends Model
{
    public $id;
    public $hospital;
    public $age;
    public $area;
    public $test;

    public function attributeLabels()
    {
        return [
            'id' => '文章ID',
            'hospital' => '社区医院',
            'age' => '年龄范围',
            'area' => '地区',
            'test'=>'测试'
        ];
    }

    public function userid(){
        $hospitals=[];
        $childs=[];
        if($this->hospital)
        {

            $hospitals=[];
            $hospitals=DoctorParent::find()->select('parentid')
                ->andFilterWhere(['in','doctorid',$this->hospital])
                ->column();
        }
        if($this->age)
        {
            $hospitalid=UserDoctor::findOne(['userid'=>$this->hospital])->hospitalid;
            $childs=[];
            foreach($this->age as $k=>$v)
            {
                $mouth = ChildInfo::getChildType($v);
                $ages = ChildInfo::find()
                    ->select('userid')
                    ->andFilterWhere(['>', 'birthday', $mouth['firstday']])
                    ->andFilterWhere(['<', 'birthday', $mouth['lastday']])
                    ->andFilterWhere(['doctorid'=>$hospitalid])
                    ->column();
                $childs=array_merge($childs,$ages);
            }
        }

        $userids=[];
        if(!$childs && !$hospitals && !$this->test)
        {
            $userids=UserLogin::find()->select('userid')->where(['!=', 'openid',''])->column();
        }elseif($hospitals && $childs){
            $userids=array_intersect($childs,$hospitals);
        }elseif($hospitals)
        {
            $userids=$hospitals;
        }elseif ($childs){
            $userids=$userids;
        }
        if($this->test)
        {
            $userids[]=79911;
            $userids[]=78256;
        }
        return $userids;
    }

    public function send(){
        $userids=$this->userid();
        $input_array=array_chunk($userids,20);
        foreach ($input_array as $v){
            $return = \Yii::$app->beanstalk
                ->putInTube('push', ['artid'=>$this->id,'userids'=>$v]);
        }
    }
    public function sendUrl(){
        $userids=$this->userid();

        $model=Chain::findOne($this->id);
        if($this->hospital)
        {
            $hos=UserDoctor::findOne(['userid'=>$this->hospital]);
            $hospital=$hos?$hos->name:'';
        }

        if($this->age)
        {
            foreach($this->age as $k=>$v)
            {
                $rs[]=\common\models\Article::$childText[$v];
            }
            $age=implode(',',$rs);
        }

        $data = [
            'first' => array('value' => $model->title."\n",),
            'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
        ];

        $content=explode(',',$model->content);
        $i=1;
        foreach($content as $k=>$v)
        {
            $data['keyword'.$i]=['value'=>$v];
            $i++;
        }

        switch ($model->type)
        {
            case 0:
                $temp=\Yii::$app->params['push'];
                break;
            case 1:
                $temp="AisY28B8z8_UDjX7xi6pay7Hh6kw420rAQwc6I1BBtE";
                break;
        }



        if($model)
        {
            foreach($userids as $k=>$v) {

                $userLogin=UserLogin::findOne(['userid'=>$v]);
                if($userLogin->openid) {
                    WechatSendTmp::send($data, $userLogin->openid, $temp,$model->url);
                }
             }
        }
    }
}