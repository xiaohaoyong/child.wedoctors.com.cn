<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DoctorParent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doctor-parent-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                <?=\common\models\UserParent::findOne($model->parentid)->mother?>
                <hr>
                <?= $form->field($model, 'parentid'); ?>
                <?= $form->field($model, 'teamid'); ?>

                <hr>
                <?= $form->field($model, 'doctorid')->dropdownList(\common\models\UserDoctor::find()->select('name')->indexBy('userid')->column(),['prompt'=>'请选择']) ?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                        'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
