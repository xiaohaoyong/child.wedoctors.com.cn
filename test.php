<?php
date_default_timezone_set('Asia/Shanghai');
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 17/9/28
 * Time: 下午3:09
 */
$url = "http://112.74.200.103:80/use_car/bookCar2C";
$time=time();
$post_data = [
    "carPlateNum"=>"京Q8K3V2",
    "devID"=>"117032100009391",
    "imei"=>"80BFF7D2-0329-4F46-81D1-B68C7055A607",
    "latitude"=>"40.00807264539930",
    "longitude"=>"116.34993408203125",
    "mobileSystem"=>"iOS",
    "operatorId"=>"1002",
    "phone"=>"15811078604",
    "sid"=>"a62546c78f90f2efed0ce2ce70e09d41",
    "signRandom"=>"5064420171222",
    "signValue"=>"376fce4c",
    "timestamp"=>"1513926511",
    "userId"=>"10286841",
    "version"=>"3.7.3",
    "wokao"=>"1",
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// post数据
curl_setopt($ch, CURLOPT_POST, 1);
// post的变量
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

$ip=get_rand_ip();
$ip1="CLIENT-IP:".$ip;
$ip2="X-FORWARDED-FOR:".$ip;
$header = [$ip1,$ip2];
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);


$output = curl_exec($ch);
curl_close($ch);

//打印获得的数据
echo date('Y-m-d H:i:s');
print_r($output);
echo "\n";

function get_rand_ip(){
    $arr_1 = array("218","218","66","66","218","218","60","60","202","204","66","66","66","59","61","60","222","221","66","59","60","60","66","218","218","62","63","64","66","66","122","211");
    $randarr= mt_rand(0,count($arr_1));
    $ip1id = $arr_1[$randarr];
    $ip2id=  round(rand(600000,  2550000)  /  10000);
    $ip3id=  round(rand(600000,  2550000)  /  10000);
    $ip4id=  round(rand(600000,  2550000)  /  10000);
    return  $ip1id . "." . $ip2id . "." . $ip3id . "." . $ip4id;
}
