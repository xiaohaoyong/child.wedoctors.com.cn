<?php
defined('__ROOT__') or define('__ROOT__', dirname(dirname(__FILE__)));
$url="http://we.child.wedoctors.com.cn";

if($_GET['we']=='new' || $_COOKIE["we"]) {
    $AppSecret = "98001ba41e010dea2861f3e0d95cbb15";
    $AppID="wx1147c2e491dfdf1d";
    $htmlurl="$url/site/childs?we=new";
    $site_url="$url/site/child?we=new";
    $index_url="$url/site/index?we=new";
    $zhidao = "G6UBxQzjJVUw8QsN2Phv0EQ2fa-3EYy6KflmkxNnuOc";
    $yiyuan = "AisY28B8z8_UDjX7xi6pay7Hh6kw420rAQwc6I1BBtE";
    $tongzhi = "76RQgMta_l3giMkUChvZM6xHC1Bwg67dKlNRndck92o";
    $chenggong = "KJKgdarbimWIeuVMAYu1VyurMUOuPve48ywc3RT6uxY";

}else{
    $AppSecret = "ea489de7644965b2f4a215956751aa83";
    $AppID="wx55f9c1fde0c7d496";
    $htmlurl="$url/site/childs";
    $site_url="$url/site/child";
    $index_url="$url/site/index";
    $zhidao = "-64ZDJj-zIPoaaawWXT5rChdfjCu8MxeB9Tp42RIcGU";
    $yiyuan = "8cOQvdECBjnzGEEH6fTe1LN9PkPHFBg0w47JmeQ7ows";
    $tongzhi = "nzMbtvtjgUGtAOSXqDE8WTjVwTDqYg0XX8dnINbdt1c";
    $chenggong = "ttPl6uAIpxdYjcELoIQWqooV1fnUvcmqn3huhbdISmY";
}
return [
    'wxXAppId'=>'wx240286cc3d77ba35',
    'wxXAppSecret'=>'cf1af7d0e91ccaedff5e7a2d57ba64ff',

    'wxUrl'=>'https://api.weixin.qq.com',
    'url'=>$url,
    'index_url'=>$index_url,
    'site_url' => $site_url,
    'passwordKey' => '2QH@6%3(87',
    'AppSecret'=>$AppSecret,
    'AppID'=>$AppID,
    'WeToken'=>'UWCE9B33CYcjaHFodunQPGCFFvfbd2Yz',
    'encodingAesKey'=>'1ktMUR9QDYv4TZNh3dr7x6KWiymVXJRysSSrZ4oWMW7',
    'imageDir'  =>'static.i.wedoctors.com.cn',
    'imageUrl'  =>'http://static.i.wedoctors.com.cn/upload/',
    'htmlUrl'  =>$htmlurl,
    'zhidao'  =>$zhidao,
    'yiyuan'  =>$yiyuan,
    'tongzhi'  =>$tongzhi,
    'chenggong'  =>$chenggong,
    'hx_url' => 'https://a1.easemob.com/1176170313115487/childhealth/',
    'hx_id' =>'YXA6KTGS4LkmEeeJI3lkRutH2g',
    'hx_secret' =>'YXA6tqys6qGSuxN4BjVzaLn84QqGszM',
];

