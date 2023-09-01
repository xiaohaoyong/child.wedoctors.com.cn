<?php
defined('__ROOT__') or define('__ROOT__', dirname(dirname(__FILE__)));
defined('__LOG__') or define('__LOG__', dirname(dirname(dirname(__ROOT__)))."/wwwlogs/child.wedoctors.com.cn/");

$url="http://we.child.wedoctors.com.cn";

$AppID="wx1147c2e491dfdf1d";
$htmlurl="$url/site/childs?we=new";
$site_url="$url/site/child?we=new";
$index_url="$url/site/index?we=new";
$zhidao = "G6UBxQzjJVUw8QsN2Phv0EQ2fa-3EYy6KflmkxNnuOc";
$yiyuan = "AisY28B8z8_UDjX7xi6pay7Hh6kw420rAQwc6I1BBtE";
$tongzhi = "76RQgMta_l3giMkUChvZM6xHC1Bwg67dKlNRndck92o";
$chenggong = "KJKgdarbimWIeuVMAYu1VyurMUOuPve48ywc3RT6uxY";
$push="HxqZZEXIH8LsPDXu-rYbojEGHvZvFV11WfllXaY2OaI";
return [
    'wxXAppId'=>'wx240286cc3d77ba35',

    'wxUrl'=>'https://api.weixin.qq.com',
    'url'=>$url,
    'index_url'=>$index_url,
    'site_url' => $site_url,
    'passwordKey' => '2QH@6%3(87',
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
    'push'  =>$push,
    'tijian'=>'ZyHYaQwXG1TWylumjaOnXI3M6Qh2TG5hbYIMSOqGQyg',
    'hx_url' => 'https://a1.easemob.com/1176170313115487/childhealth/',
    'hx_id' =>'YXA6KTGS4LkmEeeJI3lkRutH2g',
    'hx_secret' =>'YXA6tqys6qGSuxN4BjVzaLn84QqGszM',
    'ask_app_secret' =>'96daceb99579d306e0be6cd04986fdd3',
    'ask_app_id'=>'wx972d4f8601c5b227',
];

