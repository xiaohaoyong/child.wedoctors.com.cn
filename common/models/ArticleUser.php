<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_user".
 *
 * @property string $id
 * @property string $childid
 * @property string $touserid
 * @property string $artid
 * @property string $createtime
 * @property string $userid
 */
class ArticleUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['childid', 'touserid', 'artid', 'createtime', 'userid'], 'required'],
            [['childid', 'touserid', 'artid', 'createtime', 'userid'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'childid' => '儿童',
            'touserid' => '家长',
            'artid' => '文章',
            'createtime' => '推送时间',
            'userid' => '医生',
        ];
    }
    public function getArticle()
    {
        return $this->hasOne(Article::className(),['id'=>'artid']);
    }

    //关联表articleinfo
    public function GetArticleInfo()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    //查询文章信息byid
    public static function GetInfoById($id)
    {
        return ArticleUser::find()->where(['id'=>$id])->one();
    }
}
