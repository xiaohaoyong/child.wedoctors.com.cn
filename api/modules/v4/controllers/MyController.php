<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2021/7/9
 * Time: 4:23 PM
 */

namespace api\modules\v4\controllers;


use api\controllers\Controller;
use common\models\DoctorTeam;
use common\models\DoctorParent;
use common\models\UserDoctor;
use common\models\UserParent;
use common\models\ChildInfo;

use common\models\UserLogin;

class MyController extends Controller
{
    public function actionIndex(){
        $doctorParent=DoctorParent::findOne(['parentid'=>$this->userid]);
        if($doctorParent->teamid){
            $doctorTeam=DoctorTeam::findOne($doctorParent->teamid);
            $name=$doctorTeam->title;
        }else{
            $doctor=UserDoctor::findOne(['userid'=>$doctorParent->doctorid]);
            $name=$doctor->title;
        }

        $items=[
            [
                'name'=>'常用功能',
                'type'=>1,
                'list'=>[
                    ['title'=>'健康档案','img'=>'http://static.i.wedoctors.com.cn/user_index_item1.png','url'=>''],
                    ['title'=>'我的家庭','img'=>'http://static.i.wedoctors.com.cn/user_index_item2.png','url'=>''],
                    ['title'=>'我的签约','img'=>'http://static.i.wedoctors.com.cn/user_index_item3.png','url'=>'/pages/doctor/index'],
                    ['title'=>'我的预约','img'=>'http://static.i.wedoctors.com.cn/user_index_item4.png','url'=>'/pages/appoint/my'],
                    ['title'=>'家医协议','img'=>'http://static.i.wedoctors.com.cn/user_index_item10.png','url'=>'/pages/user/index/xieyi?id='.$this->userid],

                    ['title'=>'优选服务包','img'=>'http://static.i.wedoctors.com.cn/user_index_item5.png','url'=>''],
                ],
            ],
            [
                'name'=>'健康工具',
                'type'=>1,

                'list'=>[
                    ['title'=>'身高预测','img'=>'http://static.i.wedoctors.com.cn/user_index_item6.png','url'=>'/pages/tool/index/index'],
                    ['title'=>'幼儿哄睡','img'=>'http://static.i.wedoctors.com.cn/user_index_item7.png'],
                    ['title'=>'智力预测','img'=>'http://static.i.wedoctors.com.cn/user_index_item8.png'],
                    ['title'=>'视力测试','img'=>'http://static.i.wedoctors.com.cn/user_index_item9.png'],
                ],
            ],
            [
                'name'=>'辅助功能',
                'type'=>2,

                'list'=>[
                    ['title'=>'客服咨询','img'=>'http://static.i.wedoctors.com.cn/user_index_item8.png'],
                    ['title'=>'新冠疫苗相关问题解答','img'=>'http://static.i.wedoctors.com.cn/user_index_item9.png','url'=>'/pages/doctor/street'],
                    ['title'=>'常见问题','img'=>'http://static.i.wedoctors.com.cn/user_index_item10.png','url'=>'/pages//qa/index'],
                ],
            ],
        ];



        return ['name'=>$name,'items'=>$items];
    }
    public function actionLoginOut(){
        if($this->userid) {
//            $cache = \Yii::$app->rdmp;
//            $session = $cache->del($this->seaver_token);
            $this->userLogin->xopenid='';
            $this->userLogin->unionid='';
            $this->userLogin->save();
        }
    }
    public function actionXieyi(){
        $userid = $this->userid;

        $userParent = UserParent::findOne(['userid' => $userid]);


        $doctorParent = DoctorParent::findOne(['parentid' => $userid]);
        $userDoctor = UserDoctor::findOne(['userid' => $doctorParent->doctorid]);
        $child = ChildInfo::find()->where(['userid' => $userid])->all();


        return  [
            'userParent' => $userParent,
            'userid' => $userid,
            'userDoctor' => $userDoctor,
            'child' => $child,
        ];

    }

}