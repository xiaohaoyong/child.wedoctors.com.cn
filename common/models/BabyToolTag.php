<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "baby_tool_tag".
 *
 * @property int $id 主键
 * @property string $tag 标签
 * @property string $name 名称
 */
class BabyToolTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baby_tool_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag', 'name'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'tag' => '标签',
            'name' => '名称',
        ];
    }
}
