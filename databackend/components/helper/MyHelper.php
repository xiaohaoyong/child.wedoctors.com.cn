<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2016/9/23
 * Time: 16:10
 */

namespace app\components\helper;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;


class MyHelper extends Component
{
    function nomalDate($timestamp = 0)
    {
        if (!$timestamp) return '';
        $now = time();
        $mimutes = intval(($now - $timestamp) / 60);
        $hours = intval($mimutes / 60);
        $days = intval($hours / 24);
        $months = intval($days / 30);
        $years = intval($months / 12);
        if ($years) {
            return date('Y-m-d', $timestamp);
        } else if ($months) {
            return "{$months}个月前";
        } else if ($days) {
            return "{$days}天前";
        } else if ($hours) {
            return "{$hours}小时前";
        } else if ($mimutes > 0) {
            return "{$mimutes}分钟前";
        } else {
            return '刚刚';
        }
    }
}