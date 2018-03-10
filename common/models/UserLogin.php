<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_login}}".
 *
 * @property string $userid
 * @property string $password
 * @property string $openid
 * @property string $xopenid
 * @property string $hxusername
 * @property string $unionid

 */
class UserLogin extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user_login}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['userid'], 'required'],
            [['userid'], 'integer'],
            [['password'], 'string', 'max' => 32],
            [['openid'], 'string', 'max' => 30],
        ];
    }

    /**
     * 根据openid当前人数据
     * @param int $openid
     * @return  array  
     * 许升  
     */
    public static function getInfo($openid) {
        $userInfo = [];
        $userLogin = self::findOne(['openid' => $openid]);
        if (empty($userLogin)) {
            return [];
        }
        $user = User::GetInfoById($userLogin->userid);
        $userinfo = [
            'userid' => $userLogin->userid,
            'phone' => $user->phone,
            'type' => $user->type,
            'level' => $user->level,
            'openid' => $userLogin->openid,
        ];

        switch ($user->type) {
            //用户
            case 1:
                $userParent = UserParent::findOne($user->id);
                $userinfo['motherName'] = empty($userParent->mother) ? '' : $userParent->mother;
                $userinfo['motherPhone'] = empty($userParent->mother_phone) ? '' : $userParent->mother_phone;
                $userinfo['fatherName'] = empty($userParent->father) ? '' : $userParent->father;
                $userinfo['fatherPhone'] = empty($userParent->father_phone) ? '' : $userParent->father_phone;
                $userinfo['child'] = [];
                if (!empty($userParent)) {
                    $userinfo['child'] = \weixin\models\ChildInfo::GetList($user->id);
                }
                break;
            //医生
            default:
                $userDoctor = UserDoctor::GetOneById($user->id);
                $userinfo['name'] = $userDoctor->name;
                $userinfo['sex'] = $userDoctor->sex == 1 ? '男' : '女';
                $userinfo['age'] = $userDoctor->age;
                $userinfo['birthday'] = $userDoctor->birthday;
                $userinfo['docto_phone'] = $userDoctor->phone;
                $userinfo['hospitalid'] = $userDoctor->hospitalid;
                $userinfo['subject_b'] = $userDoctor->subject_b;
                $userinfo['subject_s'] = $userDoctor->subject_s;
                $userinfo['title'] = $userDoctor->title;
                $userinfo['intro'] = $userDoctor->intro;
                $userinfo['avatar'] = $userDoctor->avatar;
                $userinfo['skilful'] = $userDoctor->skilful;
                $userinfo['idnum'] = $userDoctor->idnum;
                $userinfo['province'] = $userDoctor->province;
                $userinfo['county'] = $userDoctor->county;
                $userinfo['city'] = $userDoctor->city;
                $userinfo['atitle'] = $userDoctor->atitle;
                $userinfo['otype'] = $userDoctor->otype;
                $userinfo['authimg'] = $userDoctor->authimg;
                break;
        }
        return $userinfo;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(),['id'=>'userid']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'userid' => '用户ID',
            'password' => '用户密码',
            'openid' => '微信openid',
        ];
    }

}
