<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "data_update_record".
 *
 * @property int $id
 * @property int $createtime
 * @property int $state
 * @property int $type
 * @property int $num
 * @property int $new_num
 * @property int $hospitalid
 */
class DataUpdateRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_update_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createtime', 'state', 'type', 'num', 'new_num', 'hospitalid'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'createtime' => 'Createtime',
            'state' => 'State',
            'type' => 'Type',
            'num' => 'Num',
            'new_num' => 'New Num',
            'hospitalid' => 'Hospitalid',
        ];
    }
}
