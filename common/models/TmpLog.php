<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tmp_log".
 *
 * @property int $id
 * @property string $tmpid
 * @property string $openid
 * @property int $createtime
 * @property int $fid
 */
class TmpLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tmp_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createtime', 'fid'], 'integer'],
            [['tmpid'], 'string', 'max' => 100],
            [['openid'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tmpid' => 'Tmpid',
            'openid' => 'Openid',
            'createtime' => 'Createtime',
            'fid' => 'Fid',
        ];
    }
    public function beforeSave($insert)
    {
        if($insert && !$this->createtime)
        {
            $this->createtime=time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

}