<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "appoint_order1".
 *
 * @property int $id
 * @property string $name
 * @property int $birthday
 */
class AppointOrder1 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appoint_order1';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['birthday'], 'integer'],
            [['name'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'birthday' => 'Birthday',
        ];
    }
}
