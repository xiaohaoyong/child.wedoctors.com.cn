<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%we_openid}}".
 *
 * @property int $id
 * @property string $openid 微信唯一键
 * @property int $createtime 扫码时间
 * @property int $doctorid 医生ID
 * @property string $unionid
 * @property string $xopenid
 */
class WeOpenid extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%we_openid}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createtime', 'doctorid'], 'integer'],
            [['openid'], 'string', 'max' => 50],
            [['unionid'], 'string', 'max' => 50],
            [['xopenid'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => '微信唯一键',
            'createtime' => '扫码时间',
            'doctorid' => '医生ID',
        ];
    }
}
