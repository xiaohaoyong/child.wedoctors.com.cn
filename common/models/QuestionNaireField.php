<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question_naire_field".
 *
 * @property int $id
 * @property int $qnid
 * @property int $userid
 * @property int $createtime
 */
class QuestionNaireField extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question_naire_field';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['qnid', 'userid', 'createtime'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'qnid' => 'Qnid',
            'userid' => 'Userid',
            'createtime' => 'Createtime',
        ];
    }
}