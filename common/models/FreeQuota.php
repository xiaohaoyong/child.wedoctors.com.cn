<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/4/16
 * Time: 下午4:19
 */

namespace common\models;


use yii\base\InvalidConfigException;

class FreeQuota
{
    /**
     * 重置免费名额
     */
    public static function reset(){
        $quota=self::get_quota_tmp();
        \Yii::$app->rdmp->del('free_quota');
        for($i=0;$i<$quota;$i++) {
            \Yii::$app->rdmp->lpush('free_quota', 1);
        }
    }

    /**
     * 获取限免名额
     * @return mixed
     */
    public static function get(){
        return \Yii::$app->rdmp->rpop('free_quota');
    }

    /**
     * 获取剩余免费名额数
     * @return mixed
     */
    public static function Count(){
        return \Yii::$app->rdmp->llen('free_quota');
    }

    /**
     * 获取隔日限免名额数量
     * @return int
     */
    public static function get_quota_tmp(){
        $quota_tmp=\Yii::$app->rdmp->get('quota_tmp');
        return $quota_tmp?$quota_tmp:30;
    }
    /**
     *
     * 实时修改限免名额
     */
    public static function set_quota($num){
        \Yii::$app->rdmp->del('free_quota');
        for($i=0;$i<$num;$i++) {
            \Yii::$app->rdmp->lpush('free_quota', 1);
        }
    }

    /**
     * 用户获取限免
     * @param $userid
     * @return bool
     * @throws InvalidConfigException
     */
    public static function user_quota($userid){
        $return = \Yii::$app->rdmp->zadd('user_quota_'.date('Ymd'),1,$userid);
        if($return){
            return true;
        }else{
            throw new InvalidConfigException($return);
        }
    }

    /**
     * 判断用户是否已获的今日限免名额
     * @param $userid
     * @return bool
     */
    public static function get_user_quota($userid){
        $return = \Yii::$app->rdmp->ZSCORE('user_quota_'.date('Ymd'),$userid);
        if($return){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 已使用限免用户
     * @param $userid
     * @return bool
     * @throws InvalidConfigException
     */
    public static function unset_user_quota($userid){
        $return = \Yii::$app->rdmp->zadd('unset_user_quota_'.date('Ymd'),1,$userid);
        if($return){
            return true;
        }else{
            throw new InvalidConfigException($return);
        }
    }

    /**
     * 判断用户是否已使用今日限免
     * @param $userid
     * @return bool
     */
    public static function get_unset_user_quota($userid)
    {
        $return = \Yii::$app->rdmp->ZSCORE('unset_user_quota_'.date('Ymd'),$userid);
        if($return){
            return true;
        }else{
            return false;
        }
    }



}