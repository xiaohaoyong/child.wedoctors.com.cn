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
            $date=date('Y-m-d');
            $this->_name = "Queue"."-".$doctorid .'-'.$date. "-" . $type."-".$time;
            $this->_redis = \Yii::$app->rd;
        }
    }

    public function lpush($id){
        $return=$this->_redis->lpush($this->_name,$id);
        $this->_redis->lpush($this->_name."s",$id);
        return $return;
    }
    public function rpop(){
        $return=$this->_redis->rpop($this->_name."s");
        return $return;
    }
    public function lrem($id){
        $return=$this->_redis->lrem($this->_name,0,$id);

        return $return;
    }
    public function search($id){
        $list=$this->_redis->lrange($this->_name,0,-1);
        $key=array_search($id,$list);
        return $key;
    }
    public function lrange(){
        $list=$this->_redis->lrange($this->_name,0,-1);
        return $list;
    }

}