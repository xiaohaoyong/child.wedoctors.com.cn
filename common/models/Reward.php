<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reward".
 * 打赏
 * @property int $id
 * @property int $userid
 * @property int $doctorid
 * @property double $money
 * @property int $createtime
 */
class Reward extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reward';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'doctorid', 'createtime'], 'integer'],
            [['money'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => '打赏用户',
            'doctorid' => '打赏医生',
            'money' => '金额',
            'createtime' => '时间',
        ];
    }
}
