<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2021/8/11
 * Time: 4:27 PM
 */

namespace common\models;


class AppointList
{
    private $doctorid;
    private $type;
    private $key="ANK";
    private $name;
    private $_redis;
    private $dateNum;
    public $hospitalAppoint;
    public function __construct($doctorid,$type)
    {
        $this->_redis = \Yii::$app->rd;
        $this->doctorid=$doctorid;
        $this->type=$type;
        $this->hospitalAppoint=HospitalAppoint::findOne(['doctorid'=>$doctorid,'type'=>$type]);
    }

    private function name($date,$time=0,$vid=0){
        $keys[]=$this->key;
        $keys[]=$date;
        $keys[]=$time;
        $keys[]=$vid;

        return $this->name=implode('-',$keys);
    }

    public function save($date,$time=0,$vid=0,$num=0){
        return $this->_redis->set($this->name($date,$time,$vid),$num);
    }

    /**
     * 设置日期时间段预约数
     * @param $day
     */
    public function setDateNum($day){
        $dayNum=$this->hospitalAppoint->getDateNum($day);
        foreach($dayNum as $k=>$v){
            $this->save($day,$k,0,$v);
        }
    }

    /**
     * 设置疫苗预约数量
     *
     */
    public function setVaccineNum($vid,$day){
        if(!$this->hospitalAppoint->dateNum){
            $this->setDateNum($day);
        }
        $vaccineNum=$this->hospitalAppoint->getVaccineNum($vid,$day);
        var_dump($vaccineNum);exit;
        foreach ($vaccineNum as $k=>$v){
            var_dump($v['num']);exit;
            foreach($v['num'] as $vk=>$vv) {
                $this->save($day, $vk, $vid, $vv);
            }
        }
    }
}