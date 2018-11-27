<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "evaluate".
 * 医生评价
 * @property int $id
 * @property int $userid
 * @property int $doctorid
 * @property int $score
 * @property int $createtime
 */
class Evaluate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evaluate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'doctorid', 'score', 'createtime'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => '用户',
            'doctorid' => '医生',
            'score' => '分数',
            'createtime' => '评价时间',
        ];
    }
}
