<?php

use common\models\Area;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Agreement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agreement-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'doctorid')->dropdownList([0=>'请选择']+\common\models\UserDoctor::find()->select('name')->indexBy('userid')->column()) ?>
    <?= $form->field($model, 'county')->dropdownList([0=>'请选择']+Area::$county[11]) ?>

    <?= $form->field($model, 'content')->widget('common\helpers\UEditor',[

'clientOptions' => [
    //定制菜单
    'toolbars' => [
        [
            'fullscreen', 'source', 'undo', 'redo', '|',
            'fontsize',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
            'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
            'forecolor', 'backcolor', '|',
            'lineheight', '|',
            'indent', '|',
            'simpleupload', //单图上传
        ],
    ]
    ]

]); ?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
