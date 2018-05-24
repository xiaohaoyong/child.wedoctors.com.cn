<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "doctor_parent".
 *
 * @property string $id
 * @property string $doctorid
 * @property integer $parentid
 * @property string $createtime
 * @property integer $level
 */
class DoctorParent extends \yii\db\ActiveRecord
{
    public static $levelText=[-1=>'未签约',1=>'已签约',0=>'无'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'doctor_parent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doctorid', 'parentid', 'level'], 'required'],
            [['doctorid', 'parentid', 'createtime', 'level'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'doctorid' => 'Doctorid',
            'parentid' => 'Parentid',
            'createtime' => '签约时间',
            'level' => '签约状态',
        ];
    }
    public function getDoctor()
    {
        return $this->hasOne(UserDoctor::className(),['userid'=>'parentid']);
    }


    public function getChild()
    {
        return $this->hasMany(ChildInfo::className(),['userid'=>'parentid']);
    }

    public function beforeSave($insert)
    {
        if($insert)
        {
            $this->createtime=time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }


}
