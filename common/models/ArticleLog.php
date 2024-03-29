<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_log".
 *
 * @property integer $id
 * @property integer $artid
 * @property integer $userid
 * @property integer $createtime
 */
class ArticleLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'artid', 'userid', 'createtime'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'artid' => 'Artid',
            'userid' => 'Userid',
            'createtime' => 'Createtime',
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
