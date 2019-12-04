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
    public $is_echo;
    public function __construct($name,$is_echo=false)
    {
        $this->name=$name;
        $this->is_echo=$is_echo;
    }

    public function addLog($value)
    {
        $this->lineLog .= "|,|" . $value;
        if($this->is_echo){
            echo $this->lineLog."\n";
        }
    }

    public function saveLog()
    {
        $file = __LOG__ . $this->name."-".date('Y-m-d') .".log";

        $header= date('YmdHis')."|,|".\Yii::$app->request->userIP;

        file_put_contents($file, $header.$this->lineLog . "\n", FILE_APPEND);
    }

}