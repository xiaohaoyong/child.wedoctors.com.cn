<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/7/16
 * Time: 上午12:28
 */
$doctorid=Yii::$app->user->identity->userid;
echo \yii\helpers\Html::a('下载全部','http://static.i.wedoctors.com.cn/down/'.$doctorid.'zip');
?>
<hr>
//筛选下载功能开发中。。。