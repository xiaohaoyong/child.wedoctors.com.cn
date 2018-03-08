<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "child_health_record".
 *
 * @property string $id
 * @property integer $childid
 * @property integer $createtime
 * @property integer $userid
 * @property string $content
 */
class ChildHealthRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'child_health_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['childid', 'createtime', 'userid', 'content'], 'required'],
            [['childid', 'createtime', 'userid'], 'integer'],
            [['content'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'childid' => '儿童',
            'createtime' => '时间',
            'userid' => '医生',
            'content' => '内容',
        ];
    }
}
