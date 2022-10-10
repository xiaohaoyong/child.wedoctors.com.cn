<?php

namespace common\models;

use Yii;


/**
 * This is the model class for table "qywx_userid_relation".
 * 订单主表
 * @property int $id
 * @property string $unionid
 * @property string $external_userid
 */
class QywxUseridRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%qywx_userid_relation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unionid'], 'string', 'max' => 50],
            [['external_userid'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unionid' => '微信开放平台的唯一身份标识',
            'external_userid' => '企业微信客户id，会过期',
        ];
    }

    /**
     * @param $unionid
     * @param $external_userid
     * @return bool|int
     */
    public function refreshByUnionid($unionid,$external_userid)
    {
        $model = new self();
        if (self::find()->where(['unionid'=>$unionid])->one())
        {
            return $model->updateAll(['external_userid'=>$external_userid],['unionid'=>$unionid]);
        }
        else
        {
            $model->unionid = $unionid;
            $model->external_userid = $external_userid;
            return $model->save();
        }
    }
}
