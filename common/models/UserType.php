<?php


namespace common\models;



use common\models\LoginTag;
use EasyWeChat\Factory;

class UserType
{
    public $unionid;
    public function __construct($unionid)
    {
        $this->unionid=$unionid;
    }

    //定义全部标签
    public function getType($type=1){
        $app = Factory::work(\Yii::$app->params['qywx']);
        $tags=$app->external_contact->getCorpTags();
        var_dump($tags);exit;
        return [];
    }
    //判断用户身份array（）
    public function userType($unid){

    }

    //获取用户年龄段
    public function getAge(){

    }
    //年龄段获取用户标签
    public function getAgeType(){

    }

    public static function action($unid,$qv){
        $userLogin=UserLogin::find()->where(['unionid'=>$unid])->one();
        LoginTag::updateAll(['loginid'=>$userLogin->id],['level'=>0]);

        if($userLogin){
            $qv->login_id=$userLogin->id;
            $qv->save();
            $childs=ChildInfo::find()->where(['userid'=>$userLogin->userid])->all();
            if($childs) {
                foreach ($childs as $k => $cv) {
                    $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $cv->birthday));
                    $age = $DiffDate[0] . '岁' . $DiffDate[1] . '个月';
                    $tagid=UserTag::getid($age,1);
                    LoginTag::saveTag($userLogin->id,$tagid,1);
                    echo $tagid;
                    echo "\n";
                }
            }
            $pregnancy = Pregnancy::find()->where(['familyid'=>$userLogin->userid])
                ->andWhere(['>','field11',strtotime('-43 week')])
                ->andWhere(['field49'=>0])
                ->one();
            LoginTag::deleteAll(['loginid'=>$userLogin->id,'type'=>2]);

            if($pregnancy) {
                $week = ceil(round((time() - $pregnancy->field11) / 3600 / 24) / 7);
                $tagName = "孕{$week}周";
                $tagid = UserTag::getid($tagName, 2);
                LoginTag::saveTag($userLogin->id,$tagid,2);

                echo $tagid;
                echo "\n";
            }
            LoginTag::deleteAll(['loginid'=>$userLogin->id,'type'=>3]);

            $doctorParent = DoctorParent::findOne(['parentid'=>$userLogin->userid]);
            $doctor = UserDoctor::findOne(['userid'=>$doctorParent->doctorid]);
            if($doctor){
                $hospital = $doctor->hospital->name;
                $tagid = UserTag::getid($hospital, 3);
                LoginTag::saveTag($userLogin->id,$tagid,3);

            }

        }
    }

    //用户标签入库
    public function save(){

    }

    //更新企业微信标签
    public function updatePywx(){


    }

}