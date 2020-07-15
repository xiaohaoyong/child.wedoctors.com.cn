<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/7/16
 * Time: 上午12:28
 */


$this->title='疫情调查表';
$userDoctor=\common\models\UserDoctor::findOne(['hospitalid'=>Yii::$app->user->identity->hospitalid]);
$doctorid=$userDoctor->userid;

echo \yii\helpers\Html::a('下载全部','http://static.i.wedoctors.com.cn/down/'.$doctorid.'.zip');
?>
<hr>
//筛选下载功能开发中。。。