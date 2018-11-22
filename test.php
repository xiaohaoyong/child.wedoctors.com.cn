<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/11/2
 * Time: 下午4:15
 */
$end = time();
$day=strtotime('2018-10-22');
$start = new \DateTime("@$day");
$end   = new \DateTime("@$end");
$diff  = $start->diff($end);
$month=$diff->format('%y') * 12 + $diff->format('%m');
var_dump($month);