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
use common\models\DoctorParent;
use common\models\Notice;
use common\models\Pregnancy;
use common\models\UserDoctor;
use common\models\UserParent;

class GravidaController extends Controller
{
    public function actionSave($id){
        $data=\Yii::$app->request->post();
        if(!$id) {
            //确定家庭数据
            $userParent = UserParent::findOne(['mother_id' => $data['idx']]);
            //第一胎创建家庭数据
            if (!$userParent) {
                $userParent = UserParent::findOne(['userid' => $this->userid]);
            }
            //如果添加宝妈与当前用户不同 则转移登录 二胎
            if ($userParent && $userParent->userid != $this->userid) {
                $this->userLogin->userid = $userParent->userid;
                $this->userid = $userParent->userid;
                $this->userLogin->save();
            }

            if (!$userParent) {
                $userParent = new UserParent();
                $userParent->userid = $this->userid;
                $userParent->mother_id = $data['idx'];
                $userParent->mother = $data['name'];
                $userParent->save();
            }
            $preg = Pregnancy::find()->where(['field4' => $data['idx']])->andWhere(['field11'=>strtotime($data['date'])])->one();
            if(!$preg){
                $preg=new Pregnancy();
                $preg->field4=$data['idx'];
                $preg->field1=$data['name'];
                $preg->field11=strtotime($data['date']);
                $preg->field16=strtotime($data['date']);
            }
            $doctorParent=DoctorParent::findOne(['parentid'=>$this->userid]);
            if($doctorParent && $doctorParent->doctorid){
                $preg->doctorid=UserDoctor::findOne(['userid'=>$doctorParent->doctorid])->hospitalid;
            }
            $preg->familyid=$this->userid;
            $preg->save();
            Notice::setList($this->userid, 3, ['title' => "温馨提醒及建档攻略", 'ftitle' => "医生团队提醒您请及时查看", 'id' => '/article/view/index?id=512',]);



        }else{
            $preg=Pregnancy::findOne($id);
            if($preg){
                if($preg->source!=0){
                    return new Code(20010,'您的孕期数据来源与社区，如有疑问请联系客服');
                }
                $preg->field4=$data['idx'];
                $preg->field1=$data['name'];
                $preg->field11=strtotime($data['date']);
                $preg->field16=strtotime($data['date']);
                $preg->save();
            }
            $userParent = UserParent::findOne([['userid'=>$preg->familyid]]);
            if($userParent){
                if($userParent->source!=0){
                    return new Code(20010,'您的孕期数据来源与社区，如有疑问请联系客服');
                }
                $userParent->userid = $this->userid;
                $userParent->mother_id = $data['idx'];
                $userParent->mother = $data['name'];
                $userParent->save();
            }
        }


        if($preg->firstErrors){
            return new Code(20010,implode(',',$preg->firstErrors));
        }
    }
    public function actionView($id){
        $preg= Pregnancy::findOne($id);
        if($preg) {
            $pregRow = $preg->toArray();
            $pregRow['date'] = date('Y-m-d', $preg->field11);
        }
        return $pregRow;
    }

}