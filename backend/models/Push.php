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
use common\vendor\MpWechat;
use yii\base\Model;

class Push extends Model
{
    public $id;
    public $hospital;
    public $age;
    public $area;

    public function attributeLabels()
    {
        return [
            'id' => '文章ID',
            'hospital' => '社区医院',
            'age' => '年龄范围',
            'area' => '地区',
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
            $childs=[];
            foreach($this->age as $k=>$v)
            {
                $mouth = ChildInfo::getChildType($v);
                $ages = ChildInfo::find()
                    ->select('userid')
                    ->andFilterWhere(['>', 'birthday', $mouth['firstday']])
                    ->andFilterWhere(['<', 'birthday', $mouth['lastday']])
                    ->column();
                $childs=array_merge($childs,$ages);
            }
        }
        $userids=[];
        if($this->age[0]==0 && $this->hospital[0]==0)
        {
            $userids=UserLogin::find()->select('userid')->where(['!=', 'openid',''])->column();
        }else{
            $userids=array_unique(array_merge($hospitals,$childs));
        }
        return $userids;
    }

    public function send(){
        $userids=$this->userid();


        $article=\common\models\Article::findOne($this->id);


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
            'first' => array('value' => $article->info->title."\n",),
            'keyword1' => ARRAY('value' => date('Y年m月d H:i'),),
            'keyword2' => ARRAY('value' =>'儿宝宝'),
            'keyword3' => ARRAY('value' =>'儿宝宝'),
            'keyword4' => ARRAY('value' =>$hospital.$age),
            'keyword5' => ARRAY('value' =>$article->info->title),

            'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
        ];
        $miniprogram=[
            "appid"=>\Yii::$app->params['wxXAppId'],
            "pagepath"=>"/pages/article/view/index?id=".$this->id,
        ];

        if($article)
        {
            var_dump($userids);exit;
            foreach($userids as $k=>$v) {

                $userLogin=UserLogin::findOne(['userid'=>$v]);
                if($userLogin->openid) {
                    WechatSendTmp::send($data, $userLogin->openid, \Yii::$app->params['zhidao'],'',$miniprogram);
                }
                if($article->art_type!=2)
                {
                    Notice::setList($v, 5, ['title' => $article->info->title, 'ftitle' => date('Y年m月d H:i'), 'id' => "/article/view/index?id=".$this->id,]);
                }
            }
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
            'keyword1' => ARRAY('value' =>$model->title),
            'keyword2' => ARRAY('value' =>'儿宝宝'),
            'keyword3' => ARRAY('value' =>'儿宝宝'),
            'keyword4' => ARRAY('value' => date('Y年m月d H:i'),),
            'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
        ];


        if($model)
        {
            foreach($userids as $k=>$v) {

                $userLogin=UserLogin::findOne(['userid'=>$v]);
                if($userLogin->openid) {
                    WechatSendTmp::send($data, $userLogin->openid, \Yii::$app->params['push'],$model->url);
                }
             }
        }
    }
}