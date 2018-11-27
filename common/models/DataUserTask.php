<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_user_task".
 *
 * @property int $id
 * @property int $datauserid
 * @property int $createtime
 * @property string $note
 * @property int $num
 * @property int $state
 * @property int $fd
 */
class DataUserTask extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_user_task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['datauserid', 'createtime', 'num', 'state', 'fd'], 'integer'],
            [['note'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'datauserid' => 'Datauserid',
            'createtime' => 'Createtime',
            'note' => 'Note',
            'num' => 'Num',
            'state' => 'State',
            'fd' => 'Fd',
        ];
    }
}
