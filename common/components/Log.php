<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/8/15
 * Time: 下午4:16
 */

namespace common\components;


class Log
{
    public $lineLog;
    public $name;
    public function __construct($name)
    {
        $this->name=$name;
    }

    public function addLog($value)
    {
        $this->lineLog .= "|,|" . $value;
    }

    public function saveLog()
    {
        $file = __LOG__."/" . $this->name."-".date('Y-m-d') .".log";
        file_put_contents($file, $this->lineLog . "\n", FILE_APPEND);
    }

}