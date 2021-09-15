<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "appoint_order".
 *
 * @property int $id
 * @property string $id_card
 * @property string $name
 * @property string $age
 * @property string $phone
 * @property string $birthday
 * @property string $qu
 * @property string $jiedao
 * @property string $addess
 * @property string $createtime
 */
class AppointOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appoint_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_card', 'name', 'age', 'phone', 'birthday', 'qu', 'jiedao', 'addess', 'createtime'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_card' => 'Id Card',
            'name' => 'Name',
            'age' => 'Age',
            'phone' => 'Phone',
            'birthday' => 'Birthday',
            'qu' => 'Qu',
            'jiedao' => 'Jiedao',
            'addess' => 'Addess',
            'createtime' => 'Createtime',
        ];
    }
}
