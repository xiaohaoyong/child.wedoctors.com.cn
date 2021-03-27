<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "doctor_admin".
 *
 * @property int $id
 * @property string $phone
 * @property int $hospitalid
 * @property int $createtime
 * @property int $logintime
 */
class DoctorAdmin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doctor_admin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'hospitalid', 'createtime', 'logintime'], 'integer'],
            [['hospitalid'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'hospitalid' => 'Hospitalid',
            'createtime' => 'Createtime',
            'logintime' => 'Logintime',
        ];
    }
}
