<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $id
 * @property integer $phone
 * @property integer $level
 * @property integer $type
 * @property string $createtime
 */
class Log extends \yii\db\ActiveRecord {


    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //[['phone'], 'required'],
            [['content'], 'string'],
        ];
    }
}
