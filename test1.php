<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/1/30
 * Time: 19:19
 */
/**
 * TN+驱散+2DPS+2治疗 10%
total*0.9-灭火*7-火抗1-火抗2-total*10%/(TN+5)*0.5-机器人-老八第一
/人数+1
 */


$totle=16000;
$tn=15;
$h1=120;
$h2=100;
$mh=20;
$jqr=20;
$r8=30;
$totler=38;

echo "TN:".$a1=$totle*0.1/($tn+(5.5))."\n";
echo "m:".$mh."\n";
echo "DPS 4,5:".$a1*0.5."\n";
echo "r:".($totle*0.9-$mh*7-$h1-$h2-$a1-$jqr-$r8)/($totler+1);
