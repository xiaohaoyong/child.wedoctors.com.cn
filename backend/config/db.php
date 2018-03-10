<?php
$db=[
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=139.129.246.51;dbname=child_health',
        'username' => 'wedoctors_admin',
        'password' => 'trd7V37v3PXeU9vn',
        'charset' => 'utf8',
    ],
    'dbad' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=139.129.246.51;dbname=child_admin',
        'username' => 'wedoctors_admin',
        'password' => 'trd7V37v3PXeU9vn',
        'charset' => 'utf8',
    ],

];
return $db;
