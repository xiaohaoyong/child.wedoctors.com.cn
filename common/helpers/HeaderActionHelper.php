<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2016/9/23
 * Time: 16:10
 */

namespace common\helpers;


use yii\base\Object;

class HeaderActionHelper extends Object
{
    public static $action;
    public function setAction($action)
    {
        self::$action=$action;
    }
}