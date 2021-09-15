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
    public static $typeText=[1=>'填空',2=>'选择',3=>'日期',4=>'性别'];

    public static $fieldText=[
        0=>'无',
        1=>'phone',
        2=>'idcode',
        3=>'value',
        4=>'int',
        5=>'date',
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
            'type' => '类型',
            'field'=>'验证方式',
            'qnid' => 'Qnid',
        ];
    }
}
