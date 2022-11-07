<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "login_tag".
 *
 * @property int $id
 * @property int $loginid
 * @property int $tagid
 */
class LoginTag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'login_tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['loginid', 'tagid','type','level'], 'required'],
            [['loginid', 'tagid','type','level'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'loginid' => 'Loginid',
            'tagid' => 'Tagid',
        ];
    }
    public static function saveTag($loginid,$tagid,$type){
        $loginTag = LoginTag::findOne(['loginid'=>$loginid,'tagid'=>$tagid]);
        $tag = $loginTag?$loginTag:new LoginTag();
        $tag->loginid = $loginid;
        $tag->tagid = $tagid;
        $tag->type = $type;
        $tag->level = 1;
        $tag->save();
    }
}
