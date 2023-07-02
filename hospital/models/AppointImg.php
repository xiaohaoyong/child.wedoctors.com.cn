<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "appoint_img".
 *
 * @property int $id
 * @property string $img
 * @property int $aid
 * @property int $createtime
 */
class AppointImg extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appoint_img';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['aid', 'createtime'], 'integer'],
            [['img'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'img' => 'Img',
            'aid' => 'Aid',
            'createtime' => 'Createtime',
        ];
    }
}
