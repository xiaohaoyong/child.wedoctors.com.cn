<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2021/7/9
 * Time: 4:23 PM
 */

namespace api\modules\v4\controllers;


use api\controllers\Controller;
use app\models\DoctorTeam;
use common\models\DoctorParent;
use common\models\UserDoctor;

class MyController extends Controller
{
    public function index(){
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
                    ['title'=>'健康档案','img'=>'http://static.i.wedoctors.com.cn/user_index_item1.png'],
                    ['title'=>'我的家庭','img'=>'http://static.i.wedoctors.com.cn/user_index_item2.png'],
                    ['title'=>'我的签约','img'=>'http://static.i.wedoctors.com.cn/user_index_item3.png'],
                    ['title'=>'我的预约','img'=>'http://static.i.wedoctors.com.cn/user_index_item4.png'],
                    ['title'=>'优选服务包','img'=>'http://static.i.wedoctors.com.cn/user_index_item5.png'],
                ],
            ],
            [
                'name'=>'健康工具',
                'type'=>1,

                'list'=>[
                    ['title'=>'身高预测','img'=>'http://static.i.wedoctors.com.cn/user_index_item6.png'],
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
                    ['title'=>'新冠疫苗相关问题解答','img'=>'http://static.i.wedoctors.com.cn/user_index_item9.png'],
                    ['title'=>'常见问题','img'=>'http://static.i.wedoctors.com.cn/user_index_item10.png'],
                ],
            ],
        ];



        return ['name'=>$name,'items'=>$items];
    }

}