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
    public $phones=[];
    public $hospitalid;
    public $lineLog;
    public function addLog($value)
    {
        $this->lineLog.="==".$value;
    }

    public function saveLog()
    {
        $file="log/".date('Y-m-d')."-f-".$this->hospitalid.".log";
        file_put_contents($file,$this->lineLog,FILE_APPEND);
    }
    /**
     * 主操作函数
     * @param $value
     */
    public function inputData($value){

        $this->addLog($this->hospitalid);
        $this->addLog($value[3]);
        $this->childInfo=new ChildInfo();
        $this->user=new User();
        $this->userParent=new UserParent();

        //条形码查询
        $this->childInfo=ChildInfo::findOne(['field7'=>$value[7]]);
        if($this->childInfo){
            $this->addLog("条形码");

            $this->user=User::findOne($this->childInfo->userid);
            $this->userParent=UserParent::findOne(['userid'=>$this->childInfo->userid]);
        }else{
            if(!$this->phoneSelect($value))
            {
                $this->addLog("五项");
                //五项查询
                if(!$this->fiveSelect($value))
                {
                    $this->addLog("三项");

                    if(!$this->threeSelect($value)){
                        //$this->fatherAndMother($value);
                        $this->addLog($value[3]."无");
                    }
                }
            }
        }
        $this->actionData($value);
        $this->saveLog();
    }

    /**
     * 插入数据
     * @param $value
     */
    public function actionData($value){

        if(!$this->user->id)
        {
            $user=$this->user;
            $user->phone=$this->getPhone($value);
            $user->source = 2;
            $user->type = 1;
            if(!$user->save())
            {
                $this->addLog(json_encode($user->firstErrors));
            }
            $this->user=$user;
        }

        if($this->user->id){
            //添加手机号至登录表
            foreach($this->phones as $k=>$v)
            {
                $this->upPhone($v);
            }
            //插入父母数据
            var_dump($this->userParent);exit;
            $userparent=$this->userParent;
            $userparent->userid         = $this->user->id;
            $userparent->mother         = $value[9];
            $userparent->mother_phone   = $value[37];
            $userparent->father_phone   = $value[41];

            $userparent->father         = $value[11];
            $userparent->mother_id      = $value[10];
            $userparent->father_birthday = strtotime($value[38]);
            $userparent->address        = $value[42];
            $userparent->source         = $this->hospitalid;
            $userparent->field1         = $value[1];
            $userparent->field34        = $value[40];
            $userparent->field33        = $value[39];
            $userparent->field30        = $value[36];
            $userparent->field29        = $value[35];
            $userparent->field28        = $value[34];
            $userparent->field12        = $value[13];
            $userparent->field11        = $value[12];
            if(!$userparent->save())
            {
                $this->addLog("父母信息保存失败".json_encode($userparent->firstErrors));
            }

            //插入儿童数据
            $child=$this->childInfo;
            $child->userid = $this->user->id;
            $child->name = $value[3];
            $child->gender = $value[4] == "男" ? 1 : 2;
            $child->birthday = intval(strtotime($value[5]));
            $child->source  =$this->hospitalid;
            $child->doctorid=$this->hospitalid;;
            $child->field54= $value[85];
            $child->field53= $value[70];
            $child->field52= $value[69];
            $child->field51= $value[68];
            $child->field50= $value[67];
            $child->field49= $value[66];
            $child->field48= $value[62];
            $child->field47= $value[61];
            $child->field46= $value[60];
            $child->field45= $value[59];
            $child->field44= $value[58];
            $child->field43= $value[57];
            $child->field42= $value[56];
            $child->field41= $value[55];
            $child->field40= $value[54];
            $child->field39= $value[53];
            $child->field38= $value[52];
            $child->field37= $value[51];
            $child->field27= $value[33];
            $child->field26= $value[27];
            $child->field25= $value[26];
            $child->field24= $value[25];
            $child->field23= $value[24];
            $child->field22= $value[23];
            $child->field21= $value[22];
            $child->field20= $value[21];
            $child->field19= $value[20];
            $child->field18= $value[19];
            $child->field17= $value[18];
            $child->field16= $value[17];
            $child->field15= $value[16];
            $child->field14= $value[15];
            $child->field13= $value[14];
            $child->field7= $value[7];
            $child->field6= $value[6];
            $child->field0= $value[0];
            if(!$child->save())
            {
                $this->addLog("儿童信息保存失败".json_encode($child->firstErrors));
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
        $login=UserLogin::find()
            ->andFilterWhere(['phone'=>$phone])
            ->one();
        if(!$login){
            $login =new UserLogin();
            $login->userid=$this->user->id;
            $login->password= md5(md5($phone."2QH@6%3(87"));
            $login->phone=$phone;
            if($login->save())
            {
                $this->addLog("手机导入失败：".json_encode($login->firstErrors));
            }
            return ;
        }
        $this->addLog($phone."手机号码已存在");
    }

    /**
     * 获取正确手机号
     * @param $value
     * @return mixed
     */
    public function getPhone($value){
        $father_phone=$value[37];$mother_phone=$value[41];$phone=$value[13];

        if(preg_match("/^1[34578]{1}\d{9}$/",$phone)) {
            $returnPhone=$phone;
            $this->phones[]=$phone;
        }
        if(preg_match("/^1[34578]{1}\d{9}$/",$father_phone)) {
            $returnPhone=$father_phone;
            $this->phones[]=$father_phone;

        }
        if(preg_match("/^1[34578]{1}\d{9}$/",$mother_phone)){
            $returnPhone=$mother_phone;
            $this->phones[]=$mother_phone;
        }
        return $returnPhone?$returnPhone:0;
    }

    /**
     * 根据手机号查询是否已导入过
     * @param $value
     * @return bool
     */
    public function phoneSelect($value){
        //手机号查询
        $father_phone=$value[37];$mother_phone=$value[41];$phone=$value[13];
        $this->userParent=UserParent::find()
            ->orFilterWhere(['father_phone'=>$father_phone])
            ->orFilterWhere(['mother_phone'=>$father_phone])
            ->orFilterWhere(['field12'=>$father_phone])->one();
        if(!$this->userParent){
            $this->userParent=UserParent::find()
                ->orFilterWhere(['father_phone'=>$mother_phone])
                ->orFilterWhere(['mother_phone'=>$mother_phone])
                ->orFilterWhere(['field12'=>$mother_phone])->one();
        }
        if(!$this->userParent){
            $this->userParent=UserParent::find()
                ->orFilterWhere(['father_phone'=>$phone])
                ->orFilterWhere(['mother_phone'=>$phone])
                ->orFilterWhere(['field12'=>$phone])->one();
        }
        if($this->userParent)
        {
            $this->childInfo = ChildInfo::find()
                ->andFilterWhere(['userid' =>  $this->userParent->userid])
                ->andFilterWhere(['name' => $value[3]])
                ->one();
            $this->user = User::findOne($this->childInfo->userid);
            return true;
        }
        return false;
    }

    /**
     * 根据五项数据查询是否已导入
     * @param $value
     * @return bool
     */
    public function fiveSelect($value){
        $mother     =   $value[9];
        $father     =   $value[11];
        $name       =   $value[3];
        $barthday   =   intval(strtotime($value[5]));
        $gender     =   $value[4] == "男" ? 1 : 2;

        $childInfo = ChildInfo::find()
            ->leftJoin('user_parent', '`user_parent`.`userid` = `child_info`.`userid`')
            ->andFilterWhere(["`user_parent`.`mother`"=>$mother])
            ->andFilterWhere(["`user_parent`.`father`"=>$father])
            ->andFilterWhere(["`child_info`.`name`"=>$name])
            ->andFilterWhere(["`child_info`.`birthday`"=>$barthday])
            ->andFilterWhere(["`child_info`.`gender`"=>$gender])
            ->one();
        if($childInfo)
        {
            $this->childInfo=$childInfo;
            $this->user=User::findOne($childInfo->userid);
            $this->userParent=UserParent::findOne(['userid'=>$childInfo->userid]);
            return true;
        }
        return false;
    }

    /**
     * 根据3项查询是否已导入过
     * @param $value
     * @return bool
     */
    public function threeSelect($value){
        $name       =   $value[3];
        $barthday   =   intval(strtotime($value[5]));
        $gender     =   $value[4] == "男" ? 1 : 2;

        $childInfo = ChildInfo::find()
            ->andFilterWhere(["`child_info`.`name`"=>$name])
            ->andFilterWhere(["`child_info`.`birthday`"=>$barthday])
            ->andFilterWhere(["`child_info`.`gender`"=>$gender])
            ->andFilterWhere(["`child_info`.`doctorid`"=>$this->hospitalid])
            ->one();
        if($childInfo)
        {
            $this->childInfo=$childInfo;
            $this->user=User::findOne($childInfo->userid);
            $this->userParent=UserParent::findOne(['userid'=>$childInfo->userid]);
            return true;
        }
        return false;
    }

    /**
     * 根据父母姓名查询是否已导入过
     * @param $value
     * @return bool
     */
    public function fatherAndMother($value){

        $mother     =   $value[9];
        $father     =   $value[11];


        $userParent = UserParent::find()
            ->andFilterWhere(["mother"=>$mother])
            ->andFilterWhere(["father"=>$father])
            ->andFilterWhere(["source"=>$this->hospitalid])
            ->one();

        if($userParent)
        {
            $this->childInfo=$childInfo;
            $this->user=User::findOne($childInfo->userid);
            $this->userParent=UserParent::findOne(['userid'=>$childInfo->userid]);
            return true;
        }

        return false;
    }
}