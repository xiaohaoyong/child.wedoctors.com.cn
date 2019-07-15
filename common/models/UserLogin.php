<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_login}}".
 *
 * @property string $userid
 * @property string $password
 * @property string $openid
 * @property string $xopenid
 * @property string $dopenid
 * @property string $hxusername
 * @property string $unionid
 * @property string $phone
 * @property string $createtime
 */
class UserLogin extends \yii\db\ActiveRecord
{

    public static $typeText = [0 => '儿宝宝小程序登录', 1 => '社区管理后台登录',2=>'昌平云注册'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_login}}';
    }

    public static function getOpenid($userid){

        $login=self::find()->where(['userid'=>$userid])->andWhere(["!=",'openid',''])->one();
        if($login){
            return $login->openid;
        }
        return '';
    }

    public static function getPhone($userid){

        $login=self::find()->where(['userid'=>$userid])->andWhere(["!=",'phone',''])->one();
        if($login){
            return $login->phone;
        }
        return '';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid'], 'required'],
            [['userid'], 'integer'],

            [['phone'], 'integer'],
            [['createtime'], 'integer'],
            [['password'], 'string', 'max' => 32],
            [['openid'], 'string', 'max' => 30],
            [['xopenid'], 'string', 'max' => 30],
            [['dopenid'], 'string', 'max' => 30],

            [['unionid'], 'string', 'max' => 30],

        ];
    }


    public function getHospitalid()
    {
        if ($this->type == 1) {
            $doctor = Doctors::findOne(['userid' => $this->userid]);
            return $doctor->hospitalid;
        }
    }

    public function getHospital()
    {
        if ($this->type == 1) {
            $doctor = Doctors::findOne(['userid' => $this->userid]);
            return $doctor->hospitalid;
        }
    }

    public function getCounty()
    {
        if ($this->type == 1) {
            $doctor = Doctors::findOne(['userid' => $this->userid]);
            return $doctor->county;
        }
    }
    /**
     * 根据openid当前人数据
     * @param int $openid
     * @return  array
     * 许升
     */
    public static function getInfo($openid)
    {
        $userInfo = [];
        $userLogin = self::findOne(['openid' => $openid]);
        if (empty($userLogin)) {
            return [];
        }
        $user = User::GetInfoById($userLogin->userid);
        $userinfo = [
            'userid' => $userLogin->userid,
            'phone' => $user->phone,
            'type' => $user->type,
            'level' => $user->level,
            'openid' => $userLogin->openid,
        ];

        switch ($user->type) {
            //用户
            case 1:
                $userParent = UserParent::findOne($user->id);
                $userinfo['motherName'] = empty($userParent->mother) ? '' : $userParent->mother;
                $userinfo['motherPhone'] = empty($userParent->mother_phone) ? '' : $userParent->mother_phone;
                $userinfo['fatherName'] = empty($userParent->father) ? '' : $userParent->father;
                $userinfo['fatherPhone'] = empty($userParent->father_phone) ? '' : $userParent->father_phone;
                $userinfo['child'] = [];
                if (!empty($userParent)) {
                    $userinfo['child'] = \weixin\models\ChildInfo::GetList($user->id);
                }
                break;
            //医生
            default:
                $userDoctor = UserDoctor::GetOneById($user->id);
                $userinfo['name'] = $userDoctor->name;
                $userinfo['sex'] = $userDoctor->sex == 1 ? '男' : '女';
                $userinfo['age'] = $userDoctor->age;
                $userinfo['birthday'] = $userDoctor->birthday;
                $userinfo['docto_phone'] = $userDoctor->phone;
                $userinfo['hospitalid'] = $userDoctor->hospitalid;
                $userinfo['subject_b'] = $userDoctor->subject_b;
                $userinfo['subject_s'] = $userDoctor->subject_s;
                $userinfo['title'] = $userDoctor->title;
                $userinfo['intro'] = $userDoctor->intro;
                $userinfo['avatar'] = $userDoctor->avatar;
                $userinfo['skilful'] = $userDoctor->skilful;
                $userinfo['idnum'] = $userDoctor->idnum;
                $userinfo['province'] = $userDoctor->province;
                $userinfo['county'] = $userDoctor->county;
                $userinfo['city'] = $userDoctor->city;
                $userinfo['atitle'] = $userDoctor->atitle;
                $userinfo['otype'] = $userDoctor->otype;
                $userinfo['authimg'] = $userDoctor->authimg;
                break;
        }
        return $userinfo;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userid']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userid' => '用户ID',
            'password' => '用户密码',
            'openid' => '微信openid',
            'phone' => '用户登录手机号',
            'createtime' => '创建时间',
            'dopenid'=>'医生小程序openid',

        ];
    }

    public function beforeSave($insert)
    {
        if ($insert && !$this->createtime) {
            $this->createtime = time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

}
