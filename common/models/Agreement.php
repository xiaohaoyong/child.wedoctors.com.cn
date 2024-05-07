<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "agreement".
 *
 * @property int $id
 * @property string $content
 * @property int $doctorid
 * @property int $county
 */
class Agreement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agreement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
            [['doctorid', 'county'], 'integer'],
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
            'doctorid' => 'Doctorid',
            'county' => 'County',
        ];
    }
}
