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
    public static $qywx_id=[
        1=>'etCPM_CQAAIEt4_oMnN2a5x3exhIFaKQ',
        2=>'etCPM_CQAAVHC6Gb95gsXYwttCFBWlSA',
        3=>'etCPM_CQAAvlFQKammHgFXsLZ5c0WeyA',
    ];
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
            [['name','wx_tag_id'], 'string', 'max' => 100],
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
        $app = Factory::work(\Yii::$app->params['qywx']);
        $userTag= UserTag::findOne(['name'=>$name,'type'=>$type]);
        if(!$userTag){
            $userTag = new UserTag();
            $userTag->name = $name;
            $userTag->type = $type;
            $userTag->save();
        }
        if(!$userTag->wx_tag_id){
            $params = [
                "group_id" => self::$qywx_id[$type],
                "tag" => [
                    [
                        "name" => $name,
                        "order" => $userTag->id,
                    ]
                ]
            ];

            $return=$app->external_contact->addCorpTag($params);
            if($return['errmsg']=='ok'){
                $wx_tag_id=$return['tag_group']['tag'][0]['id'];
                $userTag->wx_tag_id=$wx_tag_id;
                $userTag->save();
            }
        }

        return $userTag->id;
    }
}
