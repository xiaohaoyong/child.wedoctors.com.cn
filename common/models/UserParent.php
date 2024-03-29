<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_parent}}".
 *
 * @property int $userid 用户主键
 * @property string $mother 母亲姓名
 * @property int $mother_phone 母亲手机号
 * @property string $mother_id 母亲身份证
 * @property string $father 父亲姓名
 * @property int $father_phone 父亲手机号
 * @property int $father_birthday 父亲生日
 * @property int $state 1,散居
 * @property string $address 详细地址
 * @property int $source 导入来源医院ID
 * @property int $field44 户籍所在省
 * @property int $field45 户籍所在市
 * @property string $field34 父亲文化程度
 * @property string $field33 父亲职业
 * @property string $field30 母亲文化程度
 * @property string $field29 母亲职业
 * @property string $field28 母亲出生日期
 * @property string $field12 联系人电话
 * @property string $field11 联系人姓名
 * @property string $fieldu46 现住址地址

 * @property string $field1 户口
 * @property integer $province
 * @property string $county
 * @property integer $city
 */
class UserParent extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user_parent}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'mother_phone', 'father_phone', 'state', 'source', 'province', 'county', 'city','idtype'], 'integer'],
            [['mother_id','father_birthday','mother', 'father', 'address', 'field34', 'field33', 'field30', 'field29', 'field28', 'field12', 'field11', 'field1', 'fbirthday', 'field43', 'field44', 'field45','fieldu46','fieldp47'], 'safe'],
        ];
    }
    public static $field=[
        'mother' => '母亲姓名',
        //'mother_phone' => '母亲联系电话',
        //'mother_id' => '母亲身份证号',
        'father' => '父亲姓名',
        //'father_phone' => '父亲联系电话',
        'father_birthday' => '父亲生日',
        'state' => '居住状态',
        'address' => '户籍所在省（北京市）',
        'field34' => '父亲文化程度',
        'field33' => '父亲职业',
        'field30' => '母亲文化程度',
        'field29' => '母亲职业',
        'field28' => '母亲出生日期',
        'field12' => '联系人电话',
        'field11' => '联系人姓名',
        'field44' => '户籍所在省（北京市）',
        'field45' => '户籍地址区',
        'field1' => '户口',
        'fieldu46'=>'现住址地址',
        'fieldp47'=>'现住址详细',
    ];
    //通过id获取信息 刘方露
    public static function GetInfoById($userid)
    {
        return UserParent::find()->where(['userid'=>$userid])->one();
    }

    public function attributeLabels() {
        return [
            'userid' => '用户主键',
            'mother' => '母亲姓名',
            'mother_phone' => '母亲手机号',
            'mother_id' => '母亲身份证',
            'father' => '父亲姓名',
            'father_phone' => '父亲手机号',
            'father_birthday' => '父亲生日',
            'state' => '1,散居',
            'address' => '详细地址',
            'source' => '导入来源医院ID',
            'field34' => '父亲文化程度',
            'field33' => '父亲职业',
            'field30' => '母亲文化程度',
            'field29' => '母亲职业',
            'field28' => '母亲出生日期',
            'field12' => '联系人电话',
            'field11' => '联系人姓名',
            'field44' => '户籍所在省',
            'field45' => '户籍所在市',
            'field1' => '户口',
            'fieldu46'=>'现住址地址',
            'fieldp47'=>'现住址详细',

            'province' => '省', 'county' => '县', 'city' => '市',
        ];
    }
    public function getChild()
    {
        return $this->hasMany(ChildInfo::className(),['userid'=>'userid']);
    }

    public function getUser()
    {
        return $this->hasMany(User::className(),['id'=>'userid']);
    }

    public function getPhone(){
        $userLogin=UserLogin::find()->andWhere(['userid'=>$this->userid])->andWhere(['!=','phone',0])->andWhere(['>','phone',10000000000])->one();
        if($userLogin && $userLogin->phone){
            return $userLogin->phone;
        }else{
            $user=User::findOne($this->userid);
            if($user && $user->phone){
                return $user->phone;
            }else{
                if($this->mother_phone){
                    return $this->mother_phone;
                }elseif($this->father_phone){
                    return $this->father_phone;
                }
            }
        }
        return 0;
    }

    public function getPhones(){
        $userLogin=UserLogin::find()->andWhere(['userid'=>$this->userid])->andWhere(['!=','phone',0])->one();
        if($userLogin && $userLogin->phone)
        {
            $phones[]=$userLogin->phone;
        }
        $user=User::findOne($this->userid);
        if($user && $user->phone) {
            $phones[]=$user->phone;
        }
        $phones[]=$this->mother_phone;
        $phones[]=$this->father_phone;
        return array_unique($phones);
    }


    public function beforeDelete()
    {
        if($this->source=2)
        {

            //儿童信息
            $child=ChildInfo::findOne(['userid'=>$this->userid]);
            if($child)
            {
                ChildInfo::deleteAll(['userid'=>$this->userid]);
            }

            //用户登录信息
            $userL=UserLogin::findOne(['userid'=>$this->userid]);
            if($userL)
            {
                UserLogin::deleteAll(['userid'=>$this->userid]);
            }

            //微信扫码记录
            $weOpen=WeOpenid::findOne(['openid'=>$userL->openid]);
            if($weOpen)
            {
                WeOpenid::deleteAll(['openid'=>$userL->openid]);
            }

            //删除签约关系
            $doctorP=DoctorParent::findOne(['parentid'=>$this->userid]);
            if($doctorP)
            {
                DoctorParent::deleteAll(['parentid'=>$this->userid]);
            }
        }
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }


}
