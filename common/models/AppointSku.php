<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "appoint_sku".
 *
 * @property int $id
 * @property int $skuid
 * @property int $aid
 */
class AppointSku extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appoint_sku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['skuid', 'aid'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'skuid' => 'Skuid',
            'aid' => 'Aid',
        ];
    }
}
