<?php
$app = \EasyWeChat\Factory::officialAccount(\Yii::$app->params['easywechat']);
$cache = new \common\helpers\EasyRedisCache();


$app->rebind('cache', $cache);
?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.6.0.js"></script>
<script>
    wx.config(<?=$app->jssdk->buildConfig(['wx-open-launch-weapp', true]);?>);
</script>