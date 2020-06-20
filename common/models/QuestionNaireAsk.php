<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question_naire_ask".
 *
 * @property int $id
 * @property string $content
 * @property int $type
 * @property int $qnid
 * @property int $field
 */
class QuestionNaireAsk extends \yii\db\ActiveRecord
{
    public static $fieldText=[
        1=>'phone',
        2=>'idcode',
        3=>'value',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question_naire_ask';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'qnid','field'], 'integer'],
            [['content'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'type' => 'Type',
            'qnid' => 'Qnid',
        ];
    }
}
