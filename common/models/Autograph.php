<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "autograph".
 *
 * @property int $id
 * @property int $createtime
 * @property string $img
 * @property int $loginid
 * @property int $userid
 * @property int $doctorid
 */
class Autograph extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'autograph';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createtime', 'loginid','userid','doctorid'], 'integer'],
            [['img'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'createtime' => '签约时间',
            'img' => '签名',
            'userid' => '用户',
            'doctorid'=>'社区医院ID'
        ];
    }
    public function beforeSave($insert)
    {
        if($insert){
            $this->createtime=time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
