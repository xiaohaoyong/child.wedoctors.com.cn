<?php

$mpWechat = new \common\vendor\MpWechat([
    'token' => \Yii::$app->params['WeToken'],
    'appId' => \Yii::$app->params['AppID'],
    'appSecret' => \Yii::$app->params['AppSecret'],
    'encodingAesKey' => \Yii::$app->params['encodingAesKey']
]);
?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.6.0.js"></script>
<script>
    wx.config(<?=json_encode($mpWechat->jsApiConfig(['openTagList'=>['wx-open-launch-weapp']]));?>);
</script>
<wx-open-launch-weapp
        id="launch-btn"
        appid="wx9977e00637216db7"
        path="pages/lanhu_menzhenzhuye/component.html?id=1556"
>
    <script type="text/wxtag-template">
        <style>.btn { padding: 12px;width: 200px;height: 200px }</style>
        <button class="btn">打开小程序</button>
    </script>
</wx-open-launch-weapp>
<script>
    var btn = document.getElementById('launch-btn');
    btn.addEventListener('launch', function (e) {
        console.log('success');
    });
    btn.addEventListener('error', function (e) {
        console.log('fail', e.detail);
    });
</script>

