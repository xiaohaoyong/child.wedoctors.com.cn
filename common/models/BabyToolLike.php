<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "baby_tool_like".
 *
 * @property int $id
 * @property int $bid
 * @property int $userid
 * @property int $createtime
 * @property int $type
 * @property int $loginid
 */
class BabyToolLike extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'baby_tool_like';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bid', 'userid', 'createtime', 'type', 'loginid'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bid' => 'Bid',
            'userid' => 'Userid',
            'createtime' => 'Createtime',
            'type' => 'Type',
            'loginid' => 'Loginid',
        ];
    }

    public function getTag(){
        return $this->hasOne(BabyToolTag::className(),['id'=>'bid']);
    }


    public function beforeSave($insert)
    {
        if($insert){
            $this->createtime=time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
