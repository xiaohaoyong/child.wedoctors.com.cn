<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '数据上传';

/* @var $this yii\web\View */
/* @var $model common\models\Doctors */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="data-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($data, 'file')->widget(\kartik\file\FileInput::classname(), [
                    'pluginOptions' => [
                        'maxFileCount' => 10,
                        'minFileCount' => 2,
                        'allowedFileExtensions' => ['zip'],//接收的文件后缀
                    ],
                ]) ?>
                <div class="col-lg-3 control-label">
                    <?= Html::submitButton('提交') ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
