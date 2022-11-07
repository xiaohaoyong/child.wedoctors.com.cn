<?php

namespace common\models;

use EasyWeChat\Factory;
use Yii;

/**
 * This is the model class for table "user_tag".
 *
 * @property int $id
 * @property string $name
 * @property int $type 标签类型
 */
class UserTag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'type'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
        ];
    }
    public static function getid($name,$type){

        $userTag= UserTag::findOne(['name'=>$name,'type'=>$type]);
        if(!$userTag){
            $userTag = new UserTag();
            $userTag->name = $name;
            $userTag->type = $type;
            $userTag->save();
            var_dump($userTag->firstErrors);
        }
        return $userTag->id;
    }
}
