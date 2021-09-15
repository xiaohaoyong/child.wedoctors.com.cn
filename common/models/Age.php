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
    public static function computeDate($type){
        if($type==1){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-31 day')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-28 day')));
        }elseif($type==2){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-4 month')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-3 month')));
        }elseif($type==3){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-7 month')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-6 month')));
        }elseif($type==4){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-10 month')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-8 month')));
        }elseif($type==5){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-13 month')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-11 month')));
        }elseif($type==6){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-25 month')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-23 month')));
        }elseif($type==7){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-31 month')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-29 month')));
        }elseif($type==8){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-37 month')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-35 month')));
        }elseif($type==9){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-43 month')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-41 month')));
        }elseif($type==10){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-60 month')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-48 month')));
        }elseif($type==11){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-72 month')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-60 month')));
        }elseif($type==12){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-94 month')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-72 month')));
        }elseif($type==13){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-12 week')));
            $return['lastday']=strtotime(date('Y-m-d'));
        }elseif($type==14){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-20 week')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-16 week')));
        }elseif($type==15){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-24 week')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-21 week')));
        }elseif($type==16){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-32 week')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-28 week')));
        }elseif($type==17){
            $return['firstday']=strtotime(date('Y-m-d',strtotime('-40 week')));
            $return['lastday']=strtotime(date('Y-m-d',strtotime('-37 week')));
        }
        return $return;
    }

}