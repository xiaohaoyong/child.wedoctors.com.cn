<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "chain".
 *
 * @property string $id
 * @property string $url 链接地址
 * @property string $title 说明
 * @property string $content 其他字段
 * @property string $type 模板类型
 * @property string $createtime 创建时间
 */
class Chain extends \yii\db\ActiveRecord
{
    public static $typeText=[0=>'文章更新模板',1=>'调查问卷模板',2=>'体检通知'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chain';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createtime','type'], 'integer'],
            [['url'], 'string', 'max' => 255],
            [['content'], 'string', 'max' => 100],

            [['title'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => '链接地址',
            'title' => '说明',
            'content' => '其他字段',
            'type' => '模板类型',
            'createtime' => '创建时间',
        ];
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
