<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "appoint_order2".
 *
 * @property int $id
 * @property int $type 医保类型
 * @property int $zhenduan 诊断名称
 * @property int $bingcheng 病程（年）
 * @property string $jiazushu 家族史
 * @property int $field1 已贴敷年数
 * @property int $field2 去年发病次数
 * @property int $field3 去年门诊和急诊次数
 * @property int $field4 去年住院次数
 * @property int $field5 去年发病总天数
 * @property int $field6 疗效评价
 * @property int $field7 不良反应
 * @property int $aoid 关联id
 */
class AppointOrder2 extends \yii\db\ActiveRecord
{
    public static $typeText=[1=>'城镇职工',  2=>'城乡居民', 3=>'公费医疗', 4=>'自费', 5=>'其他'];
    public static $zhenduanText=[1=>'慢性支气管炎', 2=>'支气管哮喘', 3=>'慢性阻塞性肺疾病', 4=>'过敏性鼻炎', 5=>'体虚易感冒、反复咳喘（小儿及60岁以上者）'];
    public static $field6Text=[1=>'临床治愈：临床症状、体征消失或基本消失',2=>'显效：症状、体征明显改善',3=>'有效：症状、体征均有好转',4=>'无效：症状、体征无明显改善。' ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appoint_order2';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'zhenduan', 'bingcheng', 'field1', 'field2', 'field3', 'field4', 'field5', 'aoid'], 'integer'],
            [['type', 'zhenduan', 'bingcheng','jiazushu' ,'field1', 'field2', 'field3', 'field4', 'field5', 'field6', 'field7', 'aoid'], 'required'],

            ['field6', 'required', 'when' => function($model) {
                return $model->field1?true:false;
            }, 'message' => 'fieldA or fieldB is required'],
            [['jiazushu','field7'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '医保类型',
            'zhenduan' => '诊断名称',
            'bingcheng' => '病程（年）',
            'jiazushu' => '家族史',
            'field1' => '已贴敷年数',
            'field2' => '去年此病发病次数',
            'field3' => '去年因此病门诊和急诊次数',
            'field4' => '去年因此病住院次数',
            'field5' => '去年此病发病总天数',
            'field6' => '医疗效评价',
            'field7' => '不良反应',
            'aoid' => '关联id',
        ];
    }
}
