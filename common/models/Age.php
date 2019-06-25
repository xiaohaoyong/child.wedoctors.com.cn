<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/6/24
 * Time: 下午2:39
 */

namespace common\models;


class Age
{
    public function computeDate($type){
        if($type==1){
            $return['start']=strtotime(date('Y-m-d',strtotime('-30 day')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-28 day')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-4 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-3 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-7 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-6 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-10 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-8 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-13 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-11 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-25 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-23 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-31 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-29 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-37 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-35 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-43 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-41 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-60 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-48 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-72 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-60 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-94 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-72 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-4 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-3 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-4 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-3 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-4 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-3 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-4 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-3 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-4 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-3 month')));
        }elseif($type==2){
            $return['start']=strtotime(date('Y-m-d',strtotime('-4 month')));
            $return['end']=strtotime(date('Y-m-d',strtotime('-3 month')));
        }
        return $return;
    }

}