<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $id
 * @property integer $phone
 * @property integer $level
 * @property integer $type
 * @property string $createtime
 */
class User extends \yii\db\ActiveRecord {

    public static $levelText=[-1=>'后台删除',0=>'正常'];
    public static $typeText=[0=>'社区',1=>'用户',3=>'医生'];

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //[['phone'], 'required'],
            [['phone', 'level', 'type', 'createtime'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '主键',
            'phone' => '手机号',
            'level' => '账号状态 -1后台删除 >0正常',
            'type' => '用户类型0 医生 1用户',
            'createtime' => '注册时间',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => 0]);
    }

    //通过id获取信息  刘方露
    public static function GetInfoById($id)
    {
        return self::find()->where(['id'=>$id])->one();
    }

    public function getParent()
    {
        return $this->hasOne(UserParent::className(),['userid'=>'id']);
    }
    public function beforeSave($insert)
    {
        if($insert && !$this->createtime)
        {
            $this->createtime=time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function getLogin(){

        return $this->hasOne(UserLogin::className(),['userid'=>'id']);

    }
    public function beforeDelete()
    {
        if($this->source=2)
        {
            //用户详情
            $userP=UserParent::findOne(['userid'=>$this->id]);
            if($userP)
            {
                UserParent::deleteAll(['userid'=>$this->id]);
            }

            //儿童信息
            $child=ChildInfo::findOne(['userid'=>$this->id]);
            if($child)
            {
                ChildInfo::deleteAll(['userid'=>$this->id]);
            }

            //用户登录信息
            $userL=UserLogin::findOne(['userid'=>$this->id]);
            if($userL)
            {
                UserLogin::deleteAll(['userid'=>$this->id]);
            }

            //微信扫码记录
            $weOpen=WeOpenid::findOne(['unionid'=>$userL->unionid]);
            if($weOpen)
            {
                WeOpenid::deleteAll(['unionid'=>$userL->unionid]);
            }

            //删除签约关系
            $doctorP=DoctorParent::findOne(['parentid'=>$this->id]);
            if($doctorP)
            {
                DoctorParent::deleteAll(['parentid'=>$this->id]);
            }
        }
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }


}
