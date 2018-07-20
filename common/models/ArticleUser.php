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
 * @property string $level
 */
class ArticleUser extends \yii\db\ActiveRecord
{
    public static $levelText=[0=>'未查看',2=>'已查看'];
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
            'userid' => '医院',
            'level' => '是否查看',
            'child_type' => '儿童年龄',

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
