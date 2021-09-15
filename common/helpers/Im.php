<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/5/6
 * Time: 下午4:10
 */

namespace common\helpers;


class Im
{

    public static function createSystemFeedback($value){
        $obj=[
            'type'=>'system',
            'ext'=>[
                'systemType'=>'feedback',
                'systemExt'=>[
                    'value'=>$value,
                ]
            ],
        ];

        return json_encode((object)$obj);
    }

}