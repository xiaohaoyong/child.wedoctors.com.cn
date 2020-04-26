<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

?>
<style>
    .wz-content{
        margin:0 10px;
        padding: 20px 0;
        background: #F7F7F7;
    }
</style>

<div class="content-wrapper">
    <!--<section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1>
                <?php
                if ($this->title !== null) {
                    echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1>
        <?php } ?>


    </section>-->
        <?php

        if(is_array(\common\helpers\HeaderActionHelper::$action)){?>
            <div class="page-toolbar" style="position: fixed; z-index: 9999; top:100px;right: 0;border: 1px solid #999999;">
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
                        功能 <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <?php
                        $i=0;
                        foreach(\common\helpers\HeaderActionHelper::$action as $k=>$v){?>
                            <li>
                                <a href="<?=\yii\helpers\Url::to($v['url'])?>"><?=$v['name']?></a>
                            </li>
                            <?php
                            if($i%3==0){
                                echo "<li class=\"divider\"></li>";
                            }
                        }?>
                    </ul>
                </div>
            </div>
        <?php }?>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
            <?= Alert::widget() ?>
            </div>
            <?= $content ?>
        </div>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.0
    </div>
    <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
    reserved.
</footer>
<?php
$updateJs = <<<JS
jQuery(".krajee-datepicker").attr("autocomplete", "off");
JS;
$this->registerJs($updateJs);
?>

