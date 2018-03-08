<?php

namespace databackend\models\user;

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
class Hospital extends \common\models\Hospital
{
    public static $chile_type_static;

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
