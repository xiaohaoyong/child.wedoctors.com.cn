<?php

namespace console\models;

/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/6/13
 * Time: 下午3:23
 */
use common\components\Log;
use common\models\ChildInfo;
use common\models\User;
use common\models\UserLogin;
use common\models\UserParent;

class ChildInfoInput
{
    public $childInfo;
    public $userParent;
    public $user;
    public $phones = [];
    public $hospitalid;
    public $lineLog;
    public $log;

    public function addLog($value)
    {
        $this->lineLog .= "==" . $value;
        echo $this->lineLog."\n";
    }

    public function saveLog()
    {
        $file = "log/" . date('Y-m-d') . "-f-" . $this->hospitalid . ".log";
        file_put_contents($file, $this->lineLog . "\n", FILE_APPEND);
    }


    /**
     * 主操作函数
     * @param $value
     */
    public function inputData($value,$hospitalid=0)
    {
        if (preg_match("/\d+/", $value['mother_phone'],$mother_phone)) {
            $value['mother_phone'] = $mother_phone[0];
        }
        if (preg_match("/\d+/", $value['father_phone'],$father_phone)) {
            $value['father_phone'] = $father_phone[0];
        }
        if (preg_match("/\d+/", $value['field12'],$field12)) {
            $value['field12'] = $field12[0];
        }

        $this->log= new Log('childInfoUpdate');

        if($hospitalid) {
            $this->hospitalid = $hospitalid;
        }
        $this->log->addLog($this->hospitalid);
        $this->log->addLog($value['name']);
        $this->childInfo = new ChildInfo();
        $this->user = new User();
        $this->userParent = new UserParent();

        //条形码查询
        $this->childInfo = ChildInfo::findOne(['field7' => $value['field7']]);

        if ($this->childInfo) {
            $this->log->addLog("条形码");
            $this->user = User::findOne($this->childInfo->userid);
            $this->userParent = UserParent::findOne(['userid' => $this->childInfo->userid]);
        } else {
            if (!$this->phoneSelect($value)) {

                //五项查询
                if (!$this->fiveSelect($value)) {
                    if (!$this->threeSelect($value)) {
                        if(!$this->fatherAndMother($value)){
                            if(!$this->MotherId($value)) {
                                $this->log->addLog($value['name'] . "无");
                            }else{
                                $this->log->addLog("母亲身份证");
                            }
                        }else{
                            $this->log->addLog("父母");
                        }
                    }else{
                        $this->log->addLog("三项");
                    }
                }else{
                    $this->log->addLog("五项");
                }
            }else{
                $this->log->addLog("手机");
            }
        }

        if($this->childInfo){
            $return =1;
        }else{
            $return =2;
        }

        $this->childInfo = $this->childInfo ? $this->childInfo : new ChildInfo();
        $this->userParent = $this->userParent ? $this->userParent : new UserParent();
        $this->user = $this->user ? $this->user : new User();

        $this->actionData($value);
        $this->log->saveLog();
        return $return;
    }

    /**
     * 插入数据
     * @param $value
     */
    public function actionData($value)
    {

        if (!$this->user->id) {
            $user = $this->user;
            $user->phone = $this->getPhone($value);
            $user->source = 2;
            $user->type = 1;
            if (!$user->save()) {
                $this->log->addLog(json_encode($user->firstErrors));
                $this->log->saveLog();
            }
            $this->user = $user;
        }

        if ($this->user->id) {
            //添加手机号至登录表
            foreach ($this->phones as $k => $v) {
                $this->upPhone($v);
            }

            $userParent = $this->userParent;
            $userParentData=$value;
            $userParentData['userid']=$this->user->id;
            $userParentData['source']=$this->hospitalid;
            $userParentData['state']="散居" ? 1 : 2;
            $userParent->load(['UserParent'=>$userParentData]);
            $this->log->addLog("保存父母");
            if (!$userParent->save()) {
                $this->log->addLog("父母信息保存失败" . json_encode($userParent->firstErrors));
                $this->log->saveLog();
            }
            //插入儿童数据
            $child = $this->childInfo;
            $childData=$value;
            $childData['userid']=$this->user->id;
            $childData['gender']=$value['gender'] == "男" ? 1 : 2;
            $childData['birthday']=intval(strtotime($value['birthday']));
            $childData['source']=$this->hospitalid;
            $childData['admin']=$this->hospitalid;
            $child->load(['ChildInfo'=>$childData]);
            $this->log->addLog("保存儿童");
            if (!$child->save()) {
                $this->log->addLog("儿童信息保存失败" . json_encode($child->firstErrors));
                $this->log->saveLog();
            }
        }
        $this->log->addLog("END");
    }


    /**
     * 手机号注册子账号
     * @param $phone
     */
    public function upPhone($phone)
    {
        $login = UserLogin::find()
                ->andFilterWhere(['phone' => $phone])
            ->one();
        if (!$login) {
            $login = new UserLogin();
            $login->userid = $this->user->id;
            $login->password = md5(md5($phone . "2QH@6%3(87"));
            $login->phone = $phone;
            if (!$login->save()) {
                $this->log->addLog("手机导入失败：" . json_encode($login->firstErrors));
            }
            return;
        }
        $this->log->addLog($phone . "手机号码已存在");
    }

    /**
     * 获取正确手机号
     * @param $value
     * @return mixed
     */
    public function getPhone($value)
    {
        $father_phone = $value['mother_phone'];
        $mother_phone = $value['father_phone'];
        $phone = $value['field12'];

        if (preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
            $returnPhone = $phone;
            $this->phones[] = $phone;
        }
        if (preg_match("/^1[34578]{1}\d{9}$/", $father_phone)) {
            $returnPhone = $father_phone;
            $this->phones[] = $father_phone;

        }
        if (preg_match("/^1[34578]{1}\d{9}$/", $mother_phone)) {
            $returnPhone = $mother_phone;
            $this->phones[] = $mother_phone;
        }
        return $returnPhone ? $returnPhone : 0;
    }

    /**
     * 根据手机号查询是否已导入过
     * @param $value
     * @return bool
     */
    public function phoneSelect($value)
    {
        //手机号查询
        $father_phone = $value['mother_phone'];
        $mother_phone = $value['father_phone'];
        $phone = $value['field12'];
        if (preg_match("/^1[34578]{1}\d{9}$/", $mother_phone)) {
            $user = User::findOne(['phone' => $mother_phone]);
            if(!$user){
                $userParent = UserParent::find()
                    ->andFilterWhere(['mother_phone' => $mother_phone])
                    ->one();
            }
        }
        if(!$user && !$userParent && preg_match("/^1[34578]{1}\d{9}$/", $father_phone))
        {
            $user = User::findOne(['phone' => $father_phone]);
            if(!$user){
                $userParent = UserParent::find()
                    ->andFilterWhere(['father_phone' => $father_phone])
                    ->one();
            }
        }

        if(!$user && !$userParent && preg_match("/^1[34578]{1}\d{9}$/", $phone))
        {
            $user = User::findOne(['phone' => $phone]);
            if(!$user){
                $userParent = UserParent::find()
                    ->andFilterWhere(['field12' => $phone])
                    ->one();
            }
        }

        if($user){
            $this->user = $user;
            $childInfo = ChildInfo::find()
                ->andFilterWhere(['userid' => $this->user->id])
                ->andFilterWhere(['name' => $value['name']])
                ->one();
            $this->childInfo = $childInfo ? $childInfo : new ChildInfo();
            $userParent = UserParent::findOne(['userid' => $this->user->id]);
            $this->userParent = $userParent ? $userParent : new UserParent();
            return true;

        }elseif($userParent)
        {
            $this->userParent = $userParent;
            $childInfo = ChildInfo::find()
                ->andFilterWhere(['userid' => $userParent->userid])
                ->andFilterWhere(['name' => $value['name']])
                ->one();
            $user = User::findOne($childInfo->userid);
            $this->childInfo = $childInfo ? $childInfo : new ChildInfo();
            $this->user = $user ? $user : new User();
            return true;
        }
        return false;
    }

    /**
     * 根据五项数据查询是否已导入
     * @param $value
     * @return bool
     */
    public function fiveSelect($value)
    {
        $mother = $value['mother'];
        $father = $value['father'];
        $name = $value['name'];
        $barthday = intval(strtotime($value['birthday']));
        $gender = $value['gender'] == "男" ? 1 : 2;

        if($mother && $father && $name && $barthday && $gender) {
            $childInfo = ChildInfo::find()
                ->leftJoin('user_parent', '`user_parent`.`userid` = `child_info`.`userid`')
                ->andFilterWhere(["`user_parent`.`mother`" => $mother])
                ->andFilterWhere(["`user_parent`.`father`" => $father])
                ->andFilterWhere(["`child_info`.`name`" => $name])
                ->andFilterWhere(["`child_info`.`birthday`" => $barthday])
                ->andFilterWhere(["`child_info`.`gender`" => $gender])
                ->one();
            if ($childInfo) {
                $this->childInfo = $childInfo;
                $this->user = User::findOne($childInfo->userid);
                $this->userParent = UserParent::findOne(['userid' => $childInfo->userid]);
                return true;
            }
        }
        return false;
    }

    /**
     * 根据3项查询是否已导入过
     * @param $value
     * @return bool
     */
    public function threeSelect($value)
    {
        $name = $value['name'];
        $barthday = intval(strtotime($value['birthday']));
        $gender = $value['gender'] == "男" ? 1 : 2;

        $childInfo = ChildInfo::find()
            ->andFilterWhere(["`child_info`.`name`" => $name])
            ->andFilterWhere(["`child_info`.`birthday`" => $barthday])
            ->andFilterWhere(["`child_info`.`gender`" => $gender])
            ->andFilterWhere(["`child_info`.`source`" => $this->hospitalid])
            ->one();
        if ($childInfo) {
            $this->childInfo = $childInfo;
            $this->user = User::findOne($childInfo->userid);
            $this->userParent = UserParent::findOne(['userid' => $childInfo->userid]);
            return true;
        }
        return false;
    }

    /**
     * 根据父母姓名查询是否已导入过
     * @param $value
     * @return bool
     */
    public function fatherAndMother($value)
    {

        $mother = $value['mother'];
        if($mother!='' && $value['field28']!='') {
            $userParent = UserParent::find()
                ->andFilterWhere(["mother" => $mother])
                ->andFilterWhere(["field28" => $value['field28']])
                ->andFilterWhere(["source" => $this->hospitalid])
                ->orderBy('userid asc')
                ->one();

            if ($userParent) {
                $this->childInfo = ChildInfo::findOne(['name' => $value['name']]);
                $this->user = User::findOne($userParent->userid);
                $this->userParent = $userParent;
                return true;
            }
        }
        return false;
    }

    public function MotherId($value){
        $motherid = $value['mother_id'];
        if($motherid!='') {
            $userParent = UserParent::find()
                ->andFilterWhere(["mother_id" => $motherid])
                //->andFilterWhere(["field28" => $value[34]])
               // ->andFilterWhere(["source" => $this->hospitalid])
                ->orderBy('userid asc')
                ->one();

            if ($userParent) {
                $this->childInfo = ChildInfo::findOne(['name' => $value['name']]);
                $this->user = User::findOne($userParent->userid);
                $this->userParent = $userParent;
                return true;
            }
        }
        return false;
    }
}