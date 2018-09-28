<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question".
 * 提问
 * @property int $id
 * @property int $userid
 * @property int $createtime
 * @property int $childid
 * @property int $doctirid
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'createtime', 'childid', 'doctirid'], 'integer'],
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
            'createtime' => '创建时间',
            'childid' => '儿童id',
            'doctirid' => '医生ID（儿宝团队/专家）',
        ];
    }
}
