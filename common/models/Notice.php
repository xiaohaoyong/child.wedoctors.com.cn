<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/25
 * Time: 下午1:35
 */

namespace common\models;


class Notice
{

    public static $data=[
        'title','ftitle','id','type'
    ];
    public static $user=[
        1=>'体检通知',
        2=>'疫苗通知',
        3=>'官方公告',
        4=>'健康指导',
        5=>'育儿课堂',
        6=>'健康工具',
        7=>'今日知识',
        8=>'孕产期管理',

    ];
    public static $pages=[
        1=>'/user/examination/index',
        2=>'/tool/vaccine/index',
        3=>'/doctor/index',
        4=>'/article/guidance/index',
        5=>'/article/index/index',
        6=>'/tool/index/index',
        7=>'/tool/baby/index',
        8=>'/interview/list',
    ];

    public static function getList($userid){

        $redis=\Yii::$app->rdmp;
        $list=$redis->zrevrange('noticeList'.$userid,0,-1,'WITHSCORES');
        $row=[];
        foreach($list as $k=>$v)
        {
            if($k%2==0){
                $key=$v;
            }else{
                $row[$key]=$v;
            }
        }
        return $row;
    }

    public static function getRow($userid,$key){
        if(self::$user[$key]) {
            $redis = \Yii::$app->rdmp;
            $row=$redis->hget('noticeRow'.$userid,$key);

            return json_decode($row,true);
        }
    }

    public static function setList($userid,$key,$data=[],$id='',$headerPage=''){
        if($headerPage){
            $data['headerPage'] = $headerPage;
        }else {
            $data['headerPage'] = self::$pages[$key];
        }
        if($id)
        {
            $data['headerPage']=$data['headerPage']."?".$id;
        }
        if(self::$user[$key]) {
            $redis = \Yii::$app->rdmp;
            $redis->zadd('noticeList'.$userid,time(),$key);
            $redis->hset('noticeRow'.$userid,$key,json_encode($data));
        }
    }

    public static function delKey($userid,$key){
        $redis=\Yii::$app->rdmp;
        $index=$redis->ZRANK('noticeList'.$userid,$key);
        $redis->ZREMRANGEBYRANK('noticeList'.$userid,$index,$index);
    }
    public static function del($userid){
        $redis=\Yii::$app->rdmp;
        $redis->del('noticeList'.$userid);
    }

}