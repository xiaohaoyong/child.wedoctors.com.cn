<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/1/9
 * Time: 下午12:15
 */

namespace api\modules\v2\controllers;


use api\controllers\Controller;
use common\components\Code;
use common\models\Pregnancy;
use common\models\UserParent;

class GravidaController extends Controller
{
    public function actionSave(){
        $data=\Yii::$app->request->post();

        //确定家庭数据
        $userParent=UserParent::findOne(['mother_id'=>$data['idx']]);
        //第一胎创建家庭数据
        if(!$userParent){
            $userParent=UserParent::findOne(['userid'=>$this->userid]);
        }
        //如果添加宝妈与当前用户不同 则转移登录 二胎
        if($userParent && $userParent->userid!=$this->userid){
            $this->userLogin->userid=$userParent;
            $this->userid=$userParent->userid;
            $this->userLogin->save();
        }
        if(!$userParent){
            $userParent= new UserParent();
            $userParent->userid=$this->userid;
            $userParent->mother_id=$data['idx'];
            $userParent->mother=$data['name'];
            $userParent->save();
        }


        $preg=Pregnancy::find()->where(['field4'=>$data['idx']])->andWhere(['familyid'=>0])->one();
        if(!$preg){
            $preg=new Pregnancy();
            $preg->field4=$data['idx'];
            $preg->field1=$data['name'];
            $preg->field11=strtotime($data['date']);
        }
        $preg->familyid=$this->userid;
        $preg->save();
        if($preg->firstErrors){
            return new Code(20010,implode(',',$preg->firstErrors));
        }
    }

}