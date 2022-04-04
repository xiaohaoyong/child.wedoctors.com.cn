<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property string $id
 * @property integer $catid
 * @property integer $subject
 * @property integer $subject_pid
 * @property integer $level
 * @property string $createtime
 * @property integer $child_type
 * @property integer $art_type
 * @property string $num
 * @property integer $type
 * @property integer $sort
 */
class Article extends \yii\db\ActiveRecord
{
    public $article_type;
    /**
     * @inheritdoc
     */
    public static $levelText = [
        -2=>'未通过',0 => '待发布', -1 => '待审核',1=>'已发布'
    ];
    /*public static $childText=[
        0=>'全部',
        1=>'0~3月',
        2=>'3~6月',
        3=>'6~12月',
        4=>'12~18月',
        5=>'18~24月',
        6=>'24~30月',
        7=>'30~36月',
        8=>'3~6岁',
    ];*/
    public static $childMonth = [
        1 => 1,
        2 => 3,
        3 => 6,
        4 => 8,
        5 => 12,
        6 => 18,
        7 => 24,
        8 => 30,
        9 => 36,
        10 => 48,
        11 => 60,
        12 => 72,
    ];
    public static $childText = [
        0 => '全部',
        1 => '满月',
        2 => '3月龄',
        3 => '6月龄',
        4 => '8月龄',
        5 => '12月龄',
        6 => '18月龄',
        7 => '24月龄',
        8 => '30月龄',
        9 => '3岁',
        10 => '4岁',
        11 => '5岁',
        12 => '6岁',
    ];

    public static $catText = [
        5 => '育儿保健',
        3 => '饮食',
        4 => '预防疾病',
        1 => '视频',
        2 => '运动',
        6 => '官方通知'
    ];
    public static $typeText = [
        0 => "宣教",
        1 => "指导",
        2 => '官方通知',
        3 =>'提示',
    ];
    public static $artTypeText = [
        0 => "文章",
        1 => "视频",
    ];

    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject_pid', 'subject'], 'required'],
            [['is_index','sort','catid', 'level', 'createtime', 'child_type', 'num', 'type', 'art_type', 'subject', 'subject_pid'], 'integer'],
            ['article_type','articleType'],
        ];
    }
    public function articleType(){

        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'catid' => '分类',
            'level' => '数据级别',
            'createtime' => '创建时间',
            'child_type' => '针对儿童',
            'num' => '宣教次数',
            'type' => '文章类型',
            'art_type' => '内容类型',
            'subject' => '频道',
            'subject_pid' => '主频道',
            'sort' => '排序',
            'img' => '封面',
            'article_type'=>'针对用户'
        ];
    }

    public function getInfo()
    {
        return $this->hasOne(ArticleInfo::className(), ['id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if ($insert || !$this->createtime) {
            $this->createtime = time();
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
    public function afterSave($insert, $changedAttributes)
    {
        if($this->article_type){
            foreach($this->article_type as $k=>$v) {
                $data[$k][] = $this->id;
                $data[$k][] = $v;
            }
            ArticleType::deleteAll(['aid'=>$this->id]);
            Yii::$app->db->createCommand()->batchInsert(ArticleType::tableName(), ['aid','type'],
                $data
            )->execute();
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
}
