<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "baby_guide".
 *
 * @property int $id
 * @property int $sort
 * @property string $title
 * @property string $introduction
 * @property string $content
 * @property string $content_title
 */
class BabyGuide extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'baby_guide';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sort' => 'Sort',
            'title' => 'Title',
            'introduction' => 'Introduction',
            'content' => 'Content',
            'content_title' => 'Content Title',
        ];
    }
}
