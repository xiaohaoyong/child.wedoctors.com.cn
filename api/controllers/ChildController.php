<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/23
 * Time: 上午10:46
 */

namespace api\controllers;

use api\controllers\Controller;

use common\components\Code;
use common\models\Autograph;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Examination;
use common\models\Pregnancy;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\UserParent;
use common\models\WeOpenid;

class ChildController extends Controller
{
    public function actionList(){
        $data = [];
        if($this->userid) {
            $child = ChildInfo::find()->andFilterWhere(['userid' => $this->userid])->orderBy('birthday asc,id desc')->all();
            foreach ($child as $k => $v) {
                $row = $v->toArray();
                $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $v->birthday));
                $row['age'] = $DiffDate[0].'岁'.$DiffDate[1].'个月'.$DiffDate[2].'天';
                $data[] = $row;
            }
        }
        return $data;
    }
    public function actionAdd(){

        $name=\Yii::$app->request->post('name');
        $date=\Yii::$app->request->post('date');
        $sex=\Yii::$app->request->post('sex');
        $id=\Yii::$app->request->post('id');

        $child=\api\models\ChildInfo::findOne($id);
        $child=$child?$child:new \api\models\ChildInfo();
        $child->userid=$this->userid;
        $child->name=$name;
        $child->gender=$sex=='男'?1:2;
        $child->birthday=strtotime($date);
        $child->save();
        if($child->errors){
            return new Code(20010,'失败');
        }
    }
    public function actionView($id){
        $child=ChildInfo::findOne($id);
        if($child){
            $row=$child->toArray();
            $row['birthday']=date('Y-m-d',$child->birthday);
            $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $child->birthday));
            $row['age'] = $DiffDate[0].'岁'.$DiffDate[1].'个月'.$DiffDate[2].'天';
            $row['parent']=$child->parent->toArray();
            $row['parent']['mother_id1']=$this->dataDesensitization($row['parent']['mother_id'],6,8);
            $row['idcard1']=$row['idcard']?$this->dataDesensitization($row['idcard'],6,8):'';

            return $row;
        }else{
            return new Code(20010,'失败');
        }
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
    public function actionChildEx($id){
        $ex=Examination::find()->andFilterWhere(['childid'=>$id])->orderBy('field2 desc,field3 desc')->all();
        foreach($ex as $k=>$v)
        {
            $row['id']=$v->id;
            $row['tizhong']=$v->field70;
            $row['shenchang']=$v->field40;
            $row['touwei']=$v->field80;
            $row['bmi']=$v->field20;
            $row['feipang']=$v->field53;
            $row['fayu']=$v->field35;
            $row['yingyang']=$v->field15;
            $row['title']=$v->field2."岁".$v->field3."月体检";
            $row['next']=$v->field52;
            $row['date']=$v->field4;

            $data[]=$row;
        }
        return $data;
    }
    public function actionChildExView($id){
        $ex=Examination::findOne($id);
        $exa=new Examination();
        $field=$exa->attributeLabels();
        if($ex) {
            $list = $ex->toArray();
            unset($list['id']);
            unset($list['childid']);
            unset($list['source']);

            foreach ($list as $k => $v){
                $row['name']=$field[$k];
                $row['value']=$v;
                $data[$k]=$row;
            }
        }
        return $data;
    }
    /**
     * 根据条形码查找儿童
     */
    public function actionCode($code){
        $childInfo = ChildInfo::findOne(['field7'=>$code]);
        if($childInfo->userid==$this->userid){
            return new Code(21000,'宝宝已添加过！');

        }elseif($childInfo)
        {
            $parent=UserParent::findOne(['userid'=>$childInfo->userid]);
            return ['childid'=>$childInfo->id,'userid'=>$childInfo->userid,'mother'=>mb_substr($parent->mother,0,1,'utf-8')];
        }else{
            return new Code(20010,'无');
        }
    }
    /**
     * 根据条形码确认宝妈姓名
     */
    public function actionConfirm($userid,$childid,$mother){
        $parent=UserParent::findOne($userid);
        if($parent)
        {
            if($parent->mother!=$mother){
                return new Code(20010);
            }else{
                $this->userLogin->userid=$userid;
                $this->userLogin->save();
                $this->doctor_parent($userid,$childid);
            }
        }else{
            return new Code(20010);
        }
    }

    public function doctor_parent($userid,$childid){
        $doctor_parent=DoctorParent::findOne(['parentid'=>$userid]);
        if(!$doctor_parent || $doctor_parent->level!=1){

            $weOpenid=WeOpenid::findOne(['unionid'=>$this->userLogin->unionid]);
            $doctor_parent = $doctor_parent ? $doctor_parent : new DoctorParent();

            //2020年4月15日 修改 判断扫码记录是否存在 改为 是否扫描社区二维码
            if($weOpenid->doctorid) {
                $doctor_parent->doctorid = $weOpenid->doctorid;
            }else{
                $source=ChildInfo::findOne($childid)->source;
                $doctorid=UserDoctor::findOne(['hospitalid'=>$source])->userid;
                $doctor_parent->doctorid=$doctorid;
            }
            $doctor_parent->level = 1;
            $doctor_parent->parentid = $userid;
            $doctor_parent->createtime=time();
            $doctor_parent->save();
        }

    }
    /**
     * 五项添加/查询儿童
     */
    public function actionFive($childid=0){
        $params=\Yii::$app->request->post();
        if($childid){
            $child=ChildInfo::findOne($childid);
        }else{
            $child=ChildInfo::find()
                ->leftJoin('user_parent', '`user_parent`.`userid` = `child_info`.`userid`')
                ->andWhere(['user_parent.mother'=>$params['mother']])
                ->andWhere(['user_parent.father'=>$params['father']])
                ->andWhere(['child_info.name'=>$params['name']])
                ->andWhere(['child_info.birthday'=>strtotime($params['birthday'])])
                ->andWhere(['child_info.gender'=>$params['sex']])
                ->one();
            if($child) {
                if ($child->userid == $this->userid) {
                    return new Code(21000, '请勿重复添加宝宝！');
                }
                $this->userLogin->userid = $child->userid;
                $this->userLogin->save();
                $this->doctor_parent($child->userid, $child->id);
                return ['childid'=>$child->id,'userid'=>$child->userid];
            }
        }

        if(!$child){
            $child=new ChildInfo();
        }

        $parent=UserParent::findOne(['userid'=>$this->userid]);
        $parent=$parent?$parent:new UserParent();
        $parent->userid=$this->userid;
        $parent->mother=$params['mother'];
        $parent->father=$params['father'];
        $parent->save();

        $child->userid      =$this->userid;
        $child->name        =$params['name'];
        $child->birthday    =strtotime($params['birthday']);
        $child->gender      =$params['sex'];
        $doctorParent = DoctorParent::findOne(['parentid'=>$this->userid]);
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

        return ['childid'=>$child->id,'userid'=>$child->userid];
    }

    public function afterAction($action, $result)
    {
        $return = parent::afterAction($action, $result); // TODO: Change the autogenerated stub
        $controllerID = \Yii::$app->controller->id;
        $actionID = \Yii::$app->controller->action->id;
        //判断是否签名
        if($controllerID."/".$actionID == 'child/five' || $controllerID."/".$actionID == 'child/confirm'){

            $doctorParent=DoctorParent::findOne(['parentid'=>$this->userid]);
            if($doctorParent) {
                $doctor = UserDoctor::findOne(['userid' => $doctorParent->doctorid]);
                if(in_array($doctor->county,[1106,1105,1109,1116]) || in_array($doctorParent->doctorid,[143296,118080,126118,126122,160226,113896,113478,91722,175877,176156,184741,184793,190922,192821,194606,206259,206262,206260,213579,213390,213581,216592,216593,216594,219333,221895,223413,228039,240074,240185,240188,240189,240191,248035,248033,281082,281083,281085,281086,281087,281091,281092,281097])) {
                    $auto = Autograph::findOne(['userid' => $this->userid]);
                    if (!$auto) {
                        //判断是否添加了宝宝或者孕产妇
                        $child=ChildInfo::findOne(['userid'=>$this->userid]);
                        $preg=Pregnancy::findOne(['familyid'=>$this->userid]);
                        if($child || $preg) {
                           return ['code' => 30002, 'msg' => '已签约未签字'];
                        }
                    }
                }
            }
        }
        return $return;
    }
}