<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/23
 * Time: 上午10:46
 */

namespace api\modules\v2\controllers;


use common\components\Code;
use common\helpers\IdcardValidator;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Interview;
use common\models\Points;
use common\models\Pregnancy;
use common\models\UserDoctor;
use common\models\UserParent;

class ChildController extends \api\modules\v1\controllers\ChildController
{
    public function actionNewList(){
        $data = [];
        $gravida=[];
        if($this->userid) {
            $child = ChildInfo::find()->andFilterWhere(['userid' => $this->userid])->orderBy('birthday asc,id desc')->all();
            foreach ($child as $k => $v) {
                $row = $v->toArray();
                $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $v->birthday));
                $row['age'] = $DiffDate[0].'岁'.$DiffDate[1].'个月'.$DiffDate[2].'天';
                $data[] = $row;
            }

            $pregnancy = Pregnancy::find()->where(['familyid' => $this->userid])->orderBy('field11 desc')->one();
            if($pregnancy && $pregnancy->field49==0){
                $inter=Interview::findOne(['userid'=>$this->userid]);
                if(!$inter || !in_array($inter->pt_value,[1,2,3,8])){
                    $gravida['name']=$pregnancy->field1;
                    if($inter && $inter->prenatal==2) {
                        $field11='已分娩';
                    }elseif($pregnancy->weeks>38){
                        $field11='请完善追访信息';
                    }else{
                        $field11='孕'.$pregnancy->weeks."周";

                    }
                    $gravida['field11'] =$field11;

                }

            }
        }

        return ['list'=>$data,'gravida'=>$gravida];
    }
    /**
     * 五项添加/查询儿童
     */
    public function actionFive($childid=0){
        $params=\Yii::$app->request->post();

        if(isset($params['childidtype']) && $params['idcard']){
            $IdV=new IdcardValidator();
            switch ($params['childidtype'])
            {
                case 1:
                    $return=$IdV->passportVerify($params['idcard']);
                    break;
                case 2:
                    $return=$IdV->gapassport_verify($params['idcard']);
                    break;
                case 3:
                    $return=$IdV->junguanVerify($params['idcard']);
                    break;
                default:
                    $return=$IdV->idCardVerify($params['idcard']);
                    break;
            }
            if(!$return)
            {
                return new Code(20010,'儿童证件号验证失败');
            }
        }

        if(isset($params['motheridtype']) && $params['mother_id']){
            $IdV=new IdcardValidator();
            switch ($params['motheridtype'])
            {
                case 1:
                    $return=$IdV->passportVerify($params['mother_id']);
                    break;
                case 2:
                    $return=$IdV->gapassport_verify($params['mother_id']);
                    break;
                case 3:
                    $return=$IdV->junguanVerify($params['mother_id']);
                    break;
                default:
                    $return=$IdV->idCardVerify($params['mother_id']);
                    break;
            }
            if(!$return)
            {
                return new Code(20010,'妈妈证件号验证失败');
            }
        }
        if($childid){
            $child=ChildInfo::findOne($childid);
        }else{
            $child = ChildInfo::find()
                ->leftJoin('user_parent', '`user_parent`.`userid` = `child_info`.`userid`')
                ->andWhere(['user_parent.mother' => $params['mother']])
                //->andWhere(['user_parent.mother_id' => $params['mother_id']])
                ->andWhere(['child_info.name' => $params['name']])
                ->andWhere(['child_info.birthday' => strtotime($params['birthday'])])
                ->andWhere(['child_info.gender' => $params['sex']])
                ->one();
            if($child) {
                if ($child->userid == $this->userLogin->userid) {
                    return new Code(21000, '请勿重复添加宝宝！');
                }
                if($child->userid) {
                    $this->userLogin->userid = $child->userid;
                    $this->userLogin->save();
                }
                //return ['childid'=>$child->id,'userid'=>$child->userid];
            }
        }


        if(!$child){
            $child=new ChildInfo();
        }

        $parent=UserParent::findOne(['userid'=>$this->userLogin->userid]);
        $parent=$parent?$parent:new UserParent();
        $params['userid']=$this->userLogin->userid;
        $parent->load(['UserParent'=>$params]);
        $parent->save();

        $child->userid      =$this->userLogin->userid;
        $child->name        =$params['name'];
        $child->birthday    =strtotime($params['birthday']);
        $child->gender      =$params['sex'];
        if($params['idcard']) {
            $child->idcard = $params['idcard'];
            $child->field27 = $params['idcard'];
        }
        $child->field6    =$params['field6'];

        $doctorParent = DoctorParent::findOne(['parentid'=>$this->userLogin->userid]);
        if($doctorParent && $doctorParent->level==1)
        {
            $UserDoctor=UserDoctor::findOne(['userid'=>$doctorParent->doctorid]);
            $child->doctorid=$UserDoctor->hospitalid;
        }
        $child->save();


        if($child->firstErrors)
        {
            return new Code(20010,'失败');
        }
        $this->doctor_parent($child->userid, $child->id);

        $point=new Points();
        $point->addPoint($this->userid,2);

        return ['childid'=>$child->id,'userid'=>$child->userid];
    }
}