<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/1/9
 * Time: 下午12:15
 */

namespace api\modules\v4\controllers;


use api\controllers\Controller;
use common\components\Code;
use common\models\Area;
use common\models\DoctorParent;
use common\models\Notice;
use common\models\Pregnancy;
use common\models\UserDoctor;
use common\models\UserParent;

class GravidaController extends Controller
{
    public function actionSave($id){
        $data=\Yii::$app->request->post();
        $data['field11']=strtotime($data['field11']);
        $data=array_filter($data,function($e){
            if($e=='') return false;
            return true;
        });
        if(!$id) {
            //确定家庭数据
            $userParent = UserParent::findOne(['mother_id' => $data['field4']]);
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
                $userParent->mother_id = $data['field4'];
                $userParent->mother = $data['field1'];
                $userParent->save();
            }
            $preg = Pregnancy::find()->where(['field4' => $data['field4']])->andWhere(['field11'=>$data['field11']])->one();
            if(!$preg){
                $preg=new Pregnancy();
                $preg->load(['Pregnancy'=>$data]);

            }
            $doctorParent=DoctorParent::findOne(['parentid'=>$this->userid]);
            if($doctorParent && $doctorParent->doctorid){
                $preg->doctorid=UserDoctor::findOne(['userid'=>$doctorParent->doctorid])->hospitalid;
            }
            $preg->familyid=$this->userid;
            if(!$preg->save()){
                return new Code(20010,implode(',',$preg->firstErrors));
            }
            Notice::setList($this->userid, 3, ['title' => "温馨提醒及建档攻略", 'ftitle' => "医生团队提醒您请及时查看", 'id' => '/article/view/index?id=512',]);

        }else{
            $preg=Pregnancy::findOne($id);
            if($preg){
                if($preg->source!=0){
                    return new Code(20010,'您的孕期数据来源与社区，如有疑问请联系客服');
                }
                $preg->load(['Pregnancy'=>$data]);
                $preg->save();
            }
            $userParent = UserParent::findOne([['userid'=>$preg->familyid]]);
            if($userParent){
                if($userParent->source!=0){
                    return new Code(20010,'您的孕期数据来源与社区，如有疑问请联系客服');
                }
                $userParent->userid = $this->userid;
                $userParent->mother_id = $data['field4'];
                $userParent->mother = $data['field1'];
                $userParent->save();
            }
        }


        if($preg->firstErrors){
            return new Code(20010,implode(',',$preg->firstErrors));
        }
    }
    public function actionView($id){
        if($id) {
            $preg = Pregnancy::findOne($id);
            if ($preg) {
                $pregRow = $preg->toArray();
                $pregRow['date'] = date('Y-m-d', $preg->field11);
                $key=array_keys(Area::$province);
                $pregRow['field90']=$preg->field90-1;
                $pregRow['field90_name']=Pregnancy::$field90[$preg->field90];
                $pregRow['field90_id']=$preg->field90;

                $pregRow['field7']=array_search($preg->field7,$key);
                $pregRow['field7_name']=Area::$province[$preg->field7];
                $pregRow['field7_id']=$preg->field7;

                $pregRow['field39']=array_search($preg->field39,$key);
                $pregRow['field39_name']=Area::$province[$preg->field39];
                $pregRow['field39_id']=$preg->field39;

            }
        }
        $row['preg']=$pregRow;
        foreach(Area::$province as $k=>$v){
            $rs['id']=$k;
            $rs['name']=$v;
            $area[]=$rs;
        }

        $row['area']=$area;
        return $row;
    }

}