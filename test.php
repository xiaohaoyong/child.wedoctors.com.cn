<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/11/2
 * Time: 下午4:15
 */
//$end = time();
////$day=strtotime('2018-10-22');
////$start = new \DateTime("@$day");
////$end   = new \DateTime("@$end");
////$diff  = $start->diff($end);
////$month=$diff->format('%y') * 12 + $diff->format('%m');
////var_dump($month);



var_dump(1|1);exit;


$t=(string)110010;
$c=strlen($t);
for($i=0;$i<$c;$i++){
    var_dump($t[$i]);
    if((string)$t[$i]==1) {
        $d[] = pow(10, $i);
    }
}
print_r($d);