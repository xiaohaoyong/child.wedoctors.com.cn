<?php
$dbName=[
    'mp'=>"MP",
    'chat'=>"MP"
];

foreach($dbName as $k=>$v)
{
    $redis['rd'.$k]=[
        'class' => 'common\vendor\RedisConnection',
        'hostname' => '139.129.246.51',
        'port' => '6379',
        'password' => '06ef54b23a0af',
        'database' => 0,
    ];
}
return $redis;
