<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_type".
 *
 * @property int $id
 * @property int $aid
 * @property int $type
 */
class ArticleType extends \yii\db\ActiveRecord
{
    public static $typeText = [
        0 => '全部',
        20 => '孕4周',
        21 => '孕5周',
        22 => '孕6周',
        23 => '孕7周',
        24 => '孕8周',
        25 => '孕9周',
        26 => '孕10周',
        27 => '孕11周',
        28 => '孕12周',
        29 => '孕13周',
        30 => '孕14周',
        31 => '孕15周',
        32 => '孕16周',
        33 => '孕17周',
        34 => '孕18周',
        35 => '孕19周',
        36 => '孕20周',
        37 => '孕21周',
        38 => '孕22周',
        39 => '孕23周',
        40 => '孕24周',
        41 => '孕25周',
        42 => '孕26周',
        43 => '孕27周',
        44 => '孕28周',
        45 => '孕29周',
        46 => '孕30周',
        47 => '孕31周',
        48 => '孕32周',
        49 => '孕33周',
        50 => '孕34周',
        51 => '孕35周',
        52 => '孕36周',
        53 => '孕37周',
        54 => '孕38周',
        55 => '孕39周',
        56 => '孕40周',
        57 => '产后',
        1  => '满月',
        13 => '2月龄',
        2  => '3月龄',
        14 => '4月龄',
        15 => '5月龄',
        3  => '6月龄',
        16 => '7月龄',
        4  => '8月龄',
        17 => '9月龄',
        18 => '10月龄',
        19 => '11月龄',
        5  => '12月龄',
        6  => '18月龄',
        7  => '24月龄',
        8  => '30月龄',
        9  => '3岁',
        10 => '4岁',
        11 => '5岁',
        12 => '6岁',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['aid', 'type'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'aid' => '文章',
            'type' => '类型',
        ];
    }
}
