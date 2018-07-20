<?php

namespace console\models;

/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/6/13
 * Time: 下午3:23
 */
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

    public function addLog($value)
    {
        $this->lineLog .= "==" . $value;
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
    public function inputData($value)
    {

        $this->addLog($this->hospitalid);
        $this->addLog($value[3]);
        $this->childInfo = new ChildInfo();
        $this->user = new User();
        $this->userParent = new UserParent();

        //条形码查询
        $this->childInfo = ChildInfo::findOne(['field7' => $value[7]]);
        if ($this->childInfo) {
            $this->addLog("条形码");
            $this->user = User::findOne($this->childInfo->userid);
            $this->userParent = UserParent::findOne(['userid' => $this->childInfo->userid]);
        } else {
            if (!$this->phoneSelect($value)) {

                //五项查询
                if (!$this->fiveSelect($value)) {
                    if (!$this->threeSelect($value)) {
                        if(!$this->fatherAndMother($value)){
                            $this->addLog($value[3] . "无");
                        }else{
                            $this->addLog("父母");
                        }
                    }else{
                        $this->addLog("三项");
                    }
                }else{
                    $this->addLog("五项");
                }
            }else{
                $this->addLog("手机");
            }
        }

        $this->childInfo = $this->childInfo ? $this->childInfo : new ChildInfo();
        $this->userParent = $this->userParent ? $this->userParent : new UserParent();
        $this->user = $this->user ? $this->user : new User();

        $this->actionData($value);
        $this->saveLog();
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
                $this->addLog(json_encode($user->firstErrors));
            }
            $this->user = $user;
        }

        if ($this->user->id) {
            //添加手机号至登录表
            foreach ($this->phones as $k => $v) {
                $this->upPhone($v);
            }

            $userParent = $this->userParent;

            $userParentData=[
                'userid'        => $this->user->id,
                'mother'        => $value[9],
                'mother_phone'  => $value[37],
                'father_phone'  => $value[41],
                'father'        => $value[11],
                'mother_id'     => $value[10],
                'fbirthday'     => $value[38],
                'address'       => $value[42],
                'source'        => $this->hospitalid,
                'field1'        => $value[1],
                'field34'       => $value[40],
                'field33'       => $value[39],
                'field30'       => $value[36],
                'field29'       => $value[35],
                'field28'       => $value[34],
                'field12'       => $value[13],
                'field11'       => $value[12],
            ];
            $userParentData=array_filter($userParentData,function($e){
                if($e!='' || $e!=null) return true;
                return false;
            });

            foreach($userParentData as $k=>$v)
            {
                $userParent->$k=$v;
            }
            $this->addLog("保存父母");
            if (!$userParent->save()) {
                $this->addLog("父母信息保存失败" . json_encode($userParent->firstErrors));
            }

            //插入儿童数据
            $child = $this->childInfo;
            $childData=[
                'userid'    => $this->user->id,
                'name'      => $value[3],
                'gender'    => $value[4] == "男" ? 1 : 2,
                'birthday'  => intval(strtotime($value[5])),
                'source'    => $this->hospitalid,
                //'doctorid'  => $this->hospitalid,
                'field54'   => $value[85],
                'field53'   => $value[70],
                'field52'   => $value[69],
                'field51'   => $value[68],
                'field50'   => $value[67],
                'field49'   => $value[66],
                'field48'   => $value[62],
                'field47'   => $value[61],
                'field46'   => $value[60],
                'field45'   => $value[59],
                'field44'   => $value[58],
                'field43'   => $value[57],
                'field42'   => $value[56],
                'field41'   => $value[55],
                'field40'   => $value[54],
                'field39'   => $value[53],
                'field38'   => $value[52],
                'field37'   => $value[51],
                'field27'   => $value[33],
                'field26'   => $value[27],
                'field25'   => $value[26],
                'field24'   => $value[25],
                'field23'   => $value[24],
                'field22'   => $value[23],
                'field21'   => $value[22],
                'field20'   => $value[21],
                'field19'   => $value[20],
                'field18'   => $value[19],
                'field17'   => $value[18],
                'field16'   => $value[17],
                'field15'   => $value[16],
                'field14'   => $value[15],
                'field13'   => $value[14],
                'field7'    => $value[7],
                'field6'    => $value[6],
                'field0'    => $value[0],
            ];

            $childData=array_filter($childData,function($e){
                if($e!='' || $e!=null) return true;
                return false;
            });
            //var_dump($childData);
            $this->addLog("保存儿童");

            foreach($childData as $k=>$v)
            {
                $child->$k=$v;
            }
            if (!$child->save()) {
                $this->addLog("儿童信息保存失败" . json_encode($child->firstErrors));
            }
        }
        $this->addLog("END");
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
                $this->addLog("手机导入失败：" . json_encode($login->firstErrors));
            }
            return;
        }
        $this->addLog($phone . "手机号码已存在");
    }

    /**
     * 获取正确手机号
     * @param $value
     * @return mixed
     */
    public function getPhone($value)
    {
        $father_phone = $value[37];
        $mother_phone = $value[41];
        $phone = $value[13];

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
        $father_phone = $value[37];
        $mother_phone = $value[41];
        $phone = $value[13];
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
                ->andFilterWhere(['name' => $value[3]])
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
                ->andFilterWhere(['name' => $value[3]])
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
        $mother = $value[9];
        $father = $value[11];
        $name = $value[3];
        $barthday = intval(strtotime($value[5]));
        $gender = $value[4] == "男" ? 1 : 2;

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
        $name = $value[3];
        $barthday = intval(strtotime($value[5]));
        $gender = $value[4] == "男" ? 1 : 2;

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

        $mother = $value[9];
        if($mother!='' && $value[34]!='') {
            $userParent = UserParent::find()
                ->andFilterWhere(["mother" => $mother])
                ->andFilterWhere(["field28" => $value[34]])
                ->andFilterWhere(["source" => $this->hospitalid])
                ->orderBy('userid asc')
                ->one();

            if ($userParent) {
                $this->childInfo = ChildInfo::findOne(['name' => $value[3]]);
                $this->user = User::findOne($userParent->userid);
                $this->userParent = $userParent;
                return true;
            }
        }
        return false;
    }
}