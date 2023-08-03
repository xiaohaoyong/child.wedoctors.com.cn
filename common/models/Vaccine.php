<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vaccine".
 *
 * @property int $id 主键
 * @property string $disease 预防疾病
 * @property string $adverseReactions 不良反应
 * @property string $contraindications 接种禁忌
 * @property string $diseaseHarm 疾病危害
 * @property string $dealFlow 接种程序
 * @property string $name 疫苗名称
 * @property string $intervalName 建议接种时间
 * @property int $source 顺序
 * @property int $type 类型
 */
class Vaccine extends \yii\db\ActiveRecord
{
    public static $typeText=[0=>'一类',1=>'二类'];
    public static $alltype=[0=>'是',1=>'否'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vaccine';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disease', 'adverseReactions', 'contraindications', 'diseaseHarm', 'dealFlow'], 'required'],
            [['disease', 'adverseReactions', 'contraindications', 'diseaseHarm', 'dealFlow'], 'string'],
            [['alltype','source','type','adult'], 'integer'],
            [['name', 'intervalName'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'disease' => '预防疾病',
            'adverseReactions' => '不良反应',
            'contraindications' => '接种禁忌',
            'diseaseHarm' => '疾病危害',
            'dealFlow' => '接种程序',
            'name' => '疫苗名称',
            'intervalName' => '建议接种时间',
            'source' => '顺序',
            'type'=>'类型',
            'alltype'=>'是否为包含在全部中',

            'adult'=>'成人'
        ];
    }
}
