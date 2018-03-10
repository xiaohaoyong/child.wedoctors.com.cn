<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_info".
 *
 * @property integer $id
 * @property string $title
 * @property string $ftitle
 * @property string $content
 * @property string $img
 * @property string $source
 * @property string $video_url
 */
class ArticleInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title',], 'required'],
            [['id'], 'integer'],
            [['content','video_url','ftitle'], 'string'],
            [['title','ftitle'], 'string', 'max' => 50],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '文章标题',
            'ftitle' => '副标题',

            'content' => '文章内容',
            'img' =>'封面',
            'source'=>'来源',
            'video_url'=>'视频地址',

        ];
    }

    public static function getInfoById($artid)
    {
        return ArticleInfo::find()->where(['id'=>$artid])->one();
    }
}
