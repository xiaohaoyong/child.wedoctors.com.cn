<?php

namespace backend\models;

use common\models\ArticleInfo;
use Yii;

/**
 * This is the model class for table "article".
 *
 * @property string $id
 * @property integer $catid
 * @property integer $level
 * @property string $createtime
 * @property integer $child_type
 * @property string $num
 * @property integer $type
 */
class Article extends \common\models\Article
{
    /**
     * @inheritdoc
     */
    public static $levelText=[
        0=>'正常',-1=>'删除'
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
    public static $childText=[
        0=>'全部',
        1=>'满月',
        2=>'3月龄',
        3=>'6月龄',
        4=>'8月龄',
        5=>'12月龄',
        6=>'18月龄',
        7=>'24月龄',
        8=>'30月龄',
        9=>'3岁',
        10=>'4岁',
        11=>'5岁',
        12=>'6岁',
    ];

    public static $catText=[
        1=>'视频',
        2=>'运动',
        3=>'饮食',
        4=>'预防疾病',
        5=>'育儿保健',
    ];
    public static $typeText=[
        0=>"宣教",
        1=>"指导",
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
            [['catid'], 'required'],
            [['catid', 'level', 'createtime', 'child_type', 'num', 'type','art_type'], 'integer'],
        ];
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
            'art_type'=>'内容类型',
            'type' => '文章类型',
            'img' => '封面'
        ];
    }
    public function getInfo()
    {
        return $this->hasOne(ArticleInfo::className(),['id'=>'id']);
    }

}
