<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tmp_log".
 *
 * @property int $id
 * @property string $tmpid
 * @property string $openid
 * @property int $createtime
 * @property int $fid
 */
class TempUserid extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_userid';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'userid',
        ];
    }
}
