<?php

namespace databackend\models\user;

use callmez\wechat\sdk\MpWechat;
use Yii;

/**
 * This is the model class for table "user_doctor".
 *
 * @property integer $userid
 * @property string $name
 * @property integer $sex
 * @property integer $age
 * @property integer $birthday
 * @property string $phone
 * @property integer $hospitalid
 * @property integer $subject_b
 * @property integer $subject_s
 * @property integer $title
 * @property string $intro
 * @property string $avatar
 * @property string $skilful
 * @property string $idnum
 * @property integer $province
 * @property integer $county
 * @property integer $city
 * @property integer $atitle
 * @property integer $otype
 * @property string $authimg
 */
class UserDoctor extends  \common\models\UserDoctor
{
    public  static function find()
    {
        $find=parent::find();
    
        return $find->andFilterWhere(['is_guanfang'=>0]); // TODO: Change the autogenerated stub
    }
    public function getChild()
    {
        return $this->hasMany(ChildInfo::className(), ['userid' => 'parentid'])->viaTable('doctor_parent', ['doctorid' => 'userid']);
    }

    public function beforeSave($insert)
    {
        if(Yii::$app->user->identity->type !=1) {

            $this->hospitalid = Yii::$app->user->identity->hospital;
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
    public function afterSave($insert, $changedAttributes)
    {

        if($insert || !$this->qrcode)
        {
            $data=['action_name'=>"QR_LIMIT_SCENE",'action_info'=>['scene'=>['scene_id'=>$this->userid]]];
            $wechat = new MpWechat([
                'token' => \Yii::$app->params['WeToken'],
                'appId' => \Yii::$app->params['AppID'],
                'appSecret' => \Yii::$app->params['AppSecret'],
                'encodingAesKey' => \Yii::$app->params['encodingAesKey']
            ]);
            $return = $wechat->createQrCode($data);
            if(is_array($return))
            {
                $this->qrcode=$return['url'];
                $this->save();

            }
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

}
