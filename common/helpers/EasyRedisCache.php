<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2023/5/26
 * Time: 18:50
 */

namespace common\helpers;


use Psr\Cache\CacheException;

class EasyRedisCache implements \Psr\SimpleCache\CacheInterface
{
    public function __construct()
    {
        $this->redis=\Yii::$app->rdmp;
    }

    public function get($key, $default = null)
    {
        $value = $this->redis->get($key);
        return explode(',',$value);
        // your code
    }

    public function set($key, $value, $ttl = null)
    {
        $value=implode(',',$value);
        $this->redis->set($key,$value);

        // your code
    }

    public function delete($key)
    {
        $this->redis->delete($key);

        // your code
    }

    public function clear()
    {
        // your code
    }

    public function getMultiple($keys, $default = null)
    {
        var_dump('getMultiple');exit;

        // your code
    }

    public function setMultiple($values, $ttl = null)
    {
        // your code
    }

    public function deleteMultiple($keys)
    {
        // your code
    }

    public function has($key)
    {
        $value = $this->redis->get($key);
        return explode(',',$value);
        // your code
    }
}