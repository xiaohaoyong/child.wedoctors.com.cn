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

class GravidaController extends \api\modules\v2\controllers\GravidaController
{
    public function actionNewSave($id){
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

            foreach($data as $k=>$v)
            {
                if($v=='null'){
                    unset($data[$k]);
                }
            }
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
    public function actionNewView($id){
        if($id) {
            $preg = Pregnancy::findOne($id);
            if ($preg) {
                $pregRow = $preg->toArray();
                $pregRow['date'] = date('Y-m-d', $preg->field11);
            }
        }
        $row['preg']=$pregRow;
        foreach(Area::$province as $k=>$v){
            $rs['id']=$k;
            $rs['name']=$v;
            $area[]=$rs;
        }

        $row['area']=$area;
        foreach(Pregnancy::$field90 as $k=>$v){
            if(!$k) continue;
            $rs['id']=$k;
            $rs['name']=$v;
            $field90s[]=$rs;
        }
        $row['field90s']=$field90s;
        $row['preg']['field4a']=$row['preg']['field4']?$this->dataDesensitization($row['preg']['field4'],6,8):'';

        return $row;
    }
    function dataDesensitization($string, $start = 0, $length = 0, $re = '*')
    {
        if (empty($string)){
            return false;
        }
        $strarr = array();
        $mb_strlen = mb_strlen($string);
        while ($mb_strlen) {//循环把字符串变为数组
            $strarr[] = mb_substr($string, 0, 1, 'utf8');
            $string = mb_substr($string, 1, $mb_strlen, 'utf8');
            $mb_strlen = mb_strlen($string);
        }
        $strlen = count($strarr);
        $begin = $start >= 0 ? $start : ($strlen - abs($start));
        $end = $last = $strlen - 1;
        if ($length > 0) {
            $end = $begin + $length - 1;
        } elseif ($length < 0) {
            $end -= abs($length);
        }
        for ($i = $begin; $i <= $end; $i++) {
            $strarr[$i] = $re;
        }
        if ($begin >= $end || $begin >= $last || $end > $last) return false;
        return implode('', $strarr);
    }

}