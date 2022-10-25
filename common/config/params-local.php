<?php
return [
    'wxpay' => [
// 必要配置
        'app_id' => 'wx972d4f8601c5b227',
        'mch_id' => '1515515541',
        'key' => 'QExvcDU23UCyptBDeny6BszpMiXPCqAX',   // API 密钥
        //'sandbox' => true, // 设置为 false 或注释则关闭沙箱模式
        //        // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
        //        'cert_path' => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
        //        'key_path' => 'path/to/your/key',      // XXX: 绝对路径！！！！
    ],
    'map_key'=>'3BLBZ-Q3BR3-CO53J-3W6VT-O65LH-O2BAK',
    'doctor_AppID'=>'wx6835027c46b29cab',
    'doctor_AppSecret'=>'015e5ada0056319f1566578e96466df9',
    'easywechat'=>[
        'app_id' => 'wx1147c2e491dfdf1d',
        'secret' => '98001ba41e010dea2861f3e0d95cbb15',
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',
    ],
    'easyX'=>[
        'app_id' => 'wx240286cc3d77ba35',
        'secret' => 'cf1af7d0e91ccaedff5e7a2d57ba64ff',
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',
    ]
];
