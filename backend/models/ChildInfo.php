<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "child_info".
 *
 * @property integer $id
 * @property integer $userid
 * @property string $name
 * @property integer $birthday
 * @property integer $createtime
 * @property integer $gender
 * @property integer $source
 */
class ChildInfo extends \common\models\ChildInfo
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'child_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'name', 'birthday', 'createtime'], 'required'],
            [['userid', 'birthday', 'createtime', 'gender', 'source'], 'integer'],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Userid',
            'name' => 'Name',
            'birthday' => 'Birthday',
            'createtime' => 'Createtime',
            'gender' => 'Gender',
            'source' => 'Source',
        ];
    }
}
