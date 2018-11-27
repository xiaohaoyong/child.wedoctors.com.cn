<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "doctors".
 * 医生表
 * @property int $userid
 * @property string $name
 * @property int $hospitalid
 * @property string $intro
 * @property int $itemid
 * @property string $good
 * @property string $title
 * @property int $type
 * @property double $price
 * @property string $photo
 */
class Doctors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doctors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid'], 'required'],
            [['userid', 'hospitalid', 'itemid', 'type'], 'integer'],
            [['price'], 'number'],
            [['name', 'title'], 'string', 'max' => 20],
            [['intro', 'good', 'photo'], 'string', 'max' => 200],
            [['userid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'userid' => 'ID',
            'name' => '医生姓名',
            'hospitalid' => '所属医院',
            'intro' => '简介',
            'itemid' => '团队',
            'good' => '好评',
            'title' => '职称',
            'type' => '类型',
            'price' => '价格',
            'photo' => '头像',
        ];
    }
}
