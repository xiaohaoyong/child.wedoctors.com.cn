<?php
namespace common\helper;

use app\components\vendor\RedisConnection;
use app\models\UserInfo;
use app\models\Users;

class HuanxinUserHelper extends HuanxinHelper
{
    protected static $huanxin_user_prefix = 'hx_u_';
    protected static $user_prefix=[
        0=>'',
        1=>'sid_',
        2=>'qid_',
        3=>'mid_',
    ];

    /**
     * 获取医生信息
     * @param $id 医生id
     * @field string 检索字段
     * @return mixed
     */
    public static function getDoctorInfo($id,$field='*')
    {
        $info = UserInfo::findOne($id);
        if($field=='*'){
            return $info;
        }
        return isset($info->{$field}) ? $info->{$field} : "";
    }

    /**
     * @param int $id
     * @param  $usertype 用户类型(doctor,user)
     * @return string
     */
    public static function getRealname ($id)
    {
        $id = intval($id);
        $realname = self::getDoctorInfo($id,'name');
        return $realname ? $realname : '会员'.$id;
    }

    /**
     * 环信用户与club用户对应关系
     *
     * 环信用户名=(qid|uid|did).$userid
     * 环信密码 = md5(环信用户名."xywy.com/chat_huanxin")
     * 环信昵称 = 医生姓名|患者姓名
     *
     * @param string $id 用户id
     * @param string $usertype 用户类型(doctor,user,question,qyylxtid)
     * @return array();
     */
    public static function getUserInfo ($userid,$usertype=0)
    {

        $username =self::$user_prefix[$usertype].$userid;

        $key = self::$huanxin_user_prefix.$username;
        $redis = \Yii::$app->rdmp;
        if($userinfo = $redis->get($key)) {
            return unserialize($userinfo);
        }
        $password = md5($username.'wedoctors.com/huanxin');
        $nickname = self::getRealname($userid);

        $data = parent::addUser($username,$password,$nickname);

        if($data['entities'] || strpos($data['error_description'],'unique') !== false ){
            $userinfo = compact('username','password');
            $redis->set($key,serialize($userinfo));
            return $userinfo;
        }
        return false;
    }

    /**
     * 判断指定的用户是否已注册到环信
     * @param int $userid 用户id
     * @param string $usertype 用户类型
     * @return array | false
     */
    public function hasRegister($userid,$usertype=0)
    {
        $username =self::$user_prefix[$usertype].$userid;


        $key = self::$huanxin_user_prefix.$username;
        $redis = \Yii::$app->rdmp;
        if($userinfo = $redis->get($key)) {
            return unserialize($userinfo);
        }
        return false;
    }

    /**
     * 获取指定医生的环信好友对应的xywy用户id
     * @param int $did
     * @return array
     */
    public function getRelationDids($userid,$usertype=0)
    {
        $username =self::$user_prefix[$usertype].$userid;

        $friends = self::getRelation($username);
        $uids = array();
        foreach($friends as $friend) {
            $id = substr($friend,4);
            if(is_numeric($id)){
                $uids[] = $id;
            }
        }
        return $uids;
    }

    /**
     * 注册环信用户 [ 自定义前缀 ]
     * 环信用户与club用户对应关系
     * 环信用户名=(qid|uid|did).$userid
     * 环信密码 = md5(环信用户名."xywy.com/chat_huanxin")
     * 环信昵称 = 医生姓名|患者姓名
     *
     * @param string $id 用户id
     * @param string $usertype 用户类型(doctor,user,question)
     * @return array();
     */
    public static function regUserInfo ($userid,$usertype=0)
    {

        $username =self::$user_prefix[$usertype].$userid;

        $key = self::$huanxin_user_prefix.$username;
        $redis = \Yii::$app->rdmp;
        if($userinfo = $redis->get($key)) {
            return unserialize($userinfo);
        }
        $password = md5($username.'wedoctors.com/huanxin');
        $nickname = self::getRealname($userid);

        $data = parent::addUser($username,$password,$nickname);

        if($data['entities'] || strpos($data['error_description'],'unique') !== false ){
            $userinfo = compact('username','password');
            $redis->set($key,serialize($userinfo));
            return $userinfo;
        }
        return false;
    }
}
