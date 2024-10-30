<?php

namespace common\helpers;

use EasyWeChat\Factory;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class EasyWechat
{
    public static $config = [
            // 缓存主机
            'host'       => 'r-m5eplvkkblhixciozhpd.redis.rds.aliyuncs.com',
            // 缓存端口
            'port'     => '6379',
            // 缓存密码
            'password'     => '307476645z!!',
            // 缓存数据库
            'select'   => 5,
            // 缓存有效期 0表示永久缓存
            'timeout'   => 7200,
            // 缓存前缀
            'prefix'   => 'eazy'
        ];
    public static function officialAccount()
    {
        $app = Factory::officialAccount(\Yii::$app->params['easywechat']);
        $cache = new RedisAdapter(\Yii::$app->redis);
        $app->rebind('cache', $cache);
        return $app;
    }

}