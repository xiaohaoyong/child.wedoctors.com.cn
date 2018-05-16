<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "baby_tool".
 *
 * @property int $id 主键
 * @property int $tag 标签
 * @property string $content 内容
 * @property int $createtime 注册时间
 * @property int $period 分类
 */
class BabyTool extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baby_tool';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag', 'createtime', 'period'], 'integer'],
            [['content'], 'required'],
            [['content'], 'string'],
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
            'content' => '内容',
            'createtime' => '注册时间',
            'period' => '分类',
        ];
    }
}
