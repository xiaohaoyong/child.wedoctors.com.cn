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
    <section class="content-header">
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
    </section>
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
    <strong>Copyright &copy; 2017-2018 技术支持：</strong> 微医北京健康科技有限公司。
</footer>

