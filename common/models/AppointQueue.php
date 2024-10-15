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
class AppointQueue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appoint_queue';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'],'string'],
            [['appoint_date'],'date'],
            [['time_type', 'type', 'roomid', 'doctorid', 'appoint_id', 'status', 'created_at','num','is_read','sort'], 'integer'],
        ];
    }
}
