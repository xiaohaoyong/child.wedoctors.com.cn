<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "appoint_expert".
 *
 * @property int $id
 * @property string $name 专家姓名
 * @property string $info 专家简介
 * @property int $doctorid 所属社区ID
 */
class AppointExpert extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appoint_expert';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'info', 'doctorid'], 'required'],
            [['doctorid'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['info'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'name' => '姓名',
            'info' => '简介',
            'doctorid' => '社区',
        ];
    }
}
