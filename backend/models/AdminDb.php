<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 17/3/28
 * Time: 下午6:18
 */

namespace backend\models;


class AdminDb extends Models
{
    public static function getDb()
    {
        return \Yii::$app->get('dbad');
    }
}