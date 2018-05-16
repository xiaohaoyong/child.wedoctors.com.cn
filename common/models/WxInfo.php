<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wx_info".
 *
 * @property int $id
 * @property string $openid 微信唯一键
 * @property string $name 用户名
 * @property string $img 头像
 * @property int $userid 用户ID
 */
class WxInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid'], 'integer'],
            [['openid', 'name'], 'string', 'max' => 50],
            [['img'], 'string', 'max' => 100],
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
            'name' => '用户名',
            'img' => '头像',
            'userid' => '用户ID',
        ];
    }
}
