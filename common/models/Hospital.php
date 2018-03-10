<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hospital".
 *
 * @property integer $id
 * @property string $name
 * @property integer $province
 * @property integer $city
 * @property string $county
 * @property integer $type
 * @property integer $rank
 * @property integer $nature
 * @property integer $createtime
 * @property string $area
 */
class Hospital extends \yii\db\ActiveRecord
{
    public static $rankText = [
        1 => '三级甲等', 2 => '三级乙等', 3 => '三级丙等', 4 => '二级甲等',
        5 => '二级乙等', 6 => '二级丙等', 7 => '一级甲等', 8 => '一级乙等',
        9 => '一级丙等', 10 => '其他'
    ];
    //医院类别
    public static $typeText = [
        1 => '综合医院', 2 => '中医医院', 3 => '民族医院', 4 => '康复医院',
        5 => '疗养院', 6 => '口腔医院', 7 => '肿瘤医院', 8 => '妇产医院',
        9 => '妇幼保健院', 10 => '传染病医院', 11 => '皮肤病医院', 12 => '美容医院',
        13 => '精神病医院', 14 => '血液病医院', 15 => '心血管病医院', 16 => '整形外科医院',
        17 => '儿童医院', 18 => '眼科医院', 19 => '心胸医院', 20 => '骨科医院',
        21 => '五官医院', 22 => '肝胆医院', 23 => '胸科医院', 24 => '中西医结合医院',
        25 => '职业病医院', 26 => '代谢病医院', 27 => '脑科专科医院', 28 => '肛肠医院',
        29 => '消化病医院', 30 => '烧伤专科医院', 31 => '男科医院', 32 => '妇科医院',33=>'乡镇卫生院',34=>'社区医院',
    ];
    public static $natureText=[1=>'国营医院',2=>'民营医院',3=>'其他'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hospital';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['province', 'city', 'county', 'type', 'rank', 'nature', 'createtime'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['area'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '医院',
            'province' => '省',
            'city' => '市',
            'county' => '区/县',
            'type' => 'Type',
            'rank' => 'Rank',
            'nature' => 'Nature',
            'createtime' => 'Createtime',
            'area' => '详细地址',
        ];
    }
}
