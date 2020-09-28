<?php
namespace common\models\queuing;

/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/9/9
 * Time: 下午2:29
 */

class Queue
{
    public $_name;
    public $_redis;
    public function __construct($doctorid,$type,$time)
    {
        if($doctorid && $type) {
            $this->_name = "Queue"."-".$doctorid . "-" . $type."-".$time;
            $this->_redis = \Yii::$app->rdmp;
        }
    }

    public function lpush($id){
        $return=$this->_redis->lpush($this->_name,$id);
        return $return;
    }
    public function rpop(){
        $return=$this->_redis->rpop($this->_name);
        return $return;
    }

    public function search($id){
        $list=$this->_redis->lrange($this->_name,0,-1);
        $key=array_search($id,$list);
        return $key;
    }

}