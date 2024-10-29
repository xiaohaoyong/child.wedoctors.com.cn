<?php

namespace common\models;

use callmez\wechat\sdk\MpWechat;
use common\components\HttpRequest;
use EasyWeChat\Factory;
use PhpOffice\PhpSpreadsheet\Calculation\Exception;
use Yii;

/**
 * This is the model class for table "{{%we_openid}}".
 *
 * @property int $id
 * @property string $openid 微信唯一键
 * @property int $createtime 扫码时间
 * @property int $doctorid 医生ID
 * @property string $unionid
 * @property string $xopenid
 * @property string $level
 */
class WeOpenid extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%we_openid}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createtime', 'doctorid','level'], 'integer'],
            [['openid'], 'string', 'max' => 50],
            [['unionid'], 'string', 'max' => 50],
            [['xopenid'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => '微信唯一键',
            'createtime' => '扫码时间',
            'doctorid' => '医生ID',
            'xopenid' => '小程序openid',
            'unionid' => 'unionid',
            'level' => '签约关系',

        ];
    }
    public function beforeSave($insert)
    {
        if(!$this->unionid) {
            $openid=$this->openid;
            $app = Factory::officialAccount(\Yii::$app->params['easywechat']);
            $user = $app->user->get($openid);
            if(!$user['errcode']){
                $this->unionid = $user['unionid'];
            }else{
                throw new \Exception($user['errmsg'],$user['errcode']);
            }
        }
        if(!$this->createtime)
        {
            $this->createtime=time();
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public static function action($xml){
        if($xml['Event'] == 'subscribe' || $xml['Event'] == 'SCAN')
        {
            $openid = $xml['FromUserName'];

            $scene = str_replace('qrscene_', '', $xml['EventKey']);
            if($scene){
                $qrcodeid=Qrcodeid::findOne(['qrcodeid'=>$scene,'type'=>0]);
                $doctor_id=$qrcodeid->mappingid;
            }else{
                $doctor_id=0;
            }

            $weOpenid=WeOpenid::findOne(['openid'=>$openid]);
            $weOpenid = $weOpenid ? $weOpenid : new WeOpenid();
            if(!$weOpenid->doctorid)
            {
                $weOpenid->openid=$openid;
                $weOpenid->doctorid=$doctor_id;
                $weOpenid->save();
            }
            return $weOpenid;
        }
        return new \stdClass();
    }
}
