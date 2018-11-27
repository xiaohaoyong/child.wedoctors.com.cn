<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/11/21
 * Time: 上午11:59
 */

namespace api\models;


class Article extends \common\models\Article
{
    public static function find()
    {
        return parent::find()->andWhere(['>','`article`.level',0]);
    }
}