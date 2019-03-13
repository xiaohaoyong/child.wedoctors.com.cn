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


echo urlencode('http://web.child.wedoctors.com.cn/article/list?phone=15811078604&child_name=小张,小王,小李&child_birthday=20180101,20180202,20180303&parent_name=大张&sign=a828be3e4f571f257a91b19155a55fdc');
exit;

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