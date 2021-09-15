<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "doctor_team".
 *
 * @property int $id
 * @property string $title 团队名称
 * @property string $intro 团队简介
 * @property int $doctorid 关联社区
 */
class DoctorTeam extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doctor_team';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'intro', 'doctorid'], 'required'],
            [['doctorid'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['intro'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '团队名称',
            'intro' => '团队简介',
            'doctorid' => '关联社区',
        ];
    }
}
