<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/7/8
 * Time: 下午3:32
 */



?>
    <div class="login-box">
        <div class="login-logo">
            <a href="#">儿童健康管理</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">选择社区</p>
            <?php
            foreach($hospitals as $k=>$v){

                echo \yii\helpers\Html::a($v,['site/hospitals','hospitalid'=>$k],['class'=>'btn btn-success btn-lg btn-block']);

            }
            ?>


        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->