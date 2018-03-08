<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2017/12/6
 * Time: 下午3:32
 */

namespace common\components;


class Code
{
    public static $codeMessage=[
        10000=>"成功",
        20000=>"失败",
        20001=>"数据验证失败",
        20003=>"缺少参数",
        30000=>"网络错误！",
        30002=>"环信错误"
    ];

    private static $returnCode=[];
    public function __construct($code,$message=NULL)
    {
        self::$returnCode=['code'=>$code,"msg"=>$message?$message:self::$codeMessage[$code]];
    }

    public static function result($code=10000)
    {
        return ['code'=>$code,"msg"=>self::$codeMessage[$code]];
    }

    public function getCode()
    {
        return self::$returnCode;
    }
}