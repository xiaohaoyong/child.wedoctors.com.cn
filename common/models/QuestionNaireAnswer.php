<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question_naire_answer".
 *
 * @property int $id
 * @property int $answer
 * @property int $qnaid
 * @property int $userid
 */
class QuestionNaireAnswer extends \yii\db\ActiveRecord
{
    public $phone;
    public $idcode;
    public $value;
    public $int;
    public $date;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question_naire_answer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['answer','phone','idcode','value'], 'required'],
            [[ 'qnaid', 'userid','createtime','doctorid','qnfid','int'], 'integer'],
            [['value'], 'string'],
            [['phone'],'match','pattern'=>'/^1[23456789]\d{9}$/'],
            [['idcode'],'match','pattern'=>'/^[1-9]\d{5}(19|20)\d{2}[01]\d[0123]\d\d{3}[xX\d]$/'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'phone'=>'手机号码',
            'idcode'=>'身份证号码',
            'answer'=>'此题目',
            'value'=>'此题目',
            'int'=>'此题目',
            'date'=>'此题目',

        ];
    }

    public function beforeSave($insert)
    {
        if($insert && !$this->createtime){
            $this->createtime=time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
