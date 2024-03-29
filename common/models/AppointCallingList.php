<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "appoint_calling_list".
 *
 * @property int $id
 * @property int $aid
 * @property int $acid
 * @property string $openid
 * @property int $createtime
 * @property int $state
 * @property int $doctorid
 * @property int $calling
 * @property int $type
 */
class AppointCallingList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appoint_calling_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['aid', 'acid', 'createtime', 'state','type','doctorid','calling'], 'integer'],
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
            'aid' => 'Aid',
            'acid' => 'Acid',
            'openid' => 'Openid',
            'createtime' => 'Createtime',
            'calling'=>'是否叫号',
            'state' => 'State',
        ];
    }

    public static function listName($id,$doctorid,$type,$time){
        $num = AppointCallingList::find()->where(['doctorid' =>$doctorid])
            ->andWhere(['>', 'createtime', strtotime('today')])
            ->andWhere(['<', 'createtime', strtotime('+1 day')])
            ->andWhere(['<=', 'id', $id])
            ->andWhere(['time'=>$time])
            ->andWhere(['type'=>$type])
            ->orderBy('id asc')
            ->count();
        return $num;
    }
    public function beforeSave($insert)
    {
        if($insert){
            $this->createtime=time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
