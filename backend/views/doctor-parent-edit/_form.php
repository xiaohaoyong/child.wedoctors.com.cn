<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\DoctorParentEdit;
/* @var $this yii\web\View */
/* @var $model common\models\DoctorParentEdit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doctor-parent-edit-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                <div class="form-group field-doctorparentedit-doctorid">
                    <label class="control-label" for="doctorparentedit-doctorid">现社区</label>

                    <?=\common\models\Hospital::findOne(\common\models\UserDoctor::findOne(['userid'=>$model->undoctorid])->hospitalid)->name ?>


                    <div class="help-block"></div>
                </div>
                <div class="form-group field-doctorparentedit-doctorid">
                    <label class="control-label" for="doctorparentedit-doctorid">目标社区</label>
                    <?=\common\models\Hospital::findOne(\common\models\UserDoctor::findOne(['userid'=>$model->doctorid])->hospitalid)->name ?>
                    <div class="help-block"></div>
                </div>
    <?= $form->field($model, 'userid')->textInput(['disabled'=>"disabled" ]) ?>


                <div class="form-group field-doctorparentedit-doctorid">
                    <label class="control-label" for="doctorparentedit-doctorid">资料</label>
                    <?=Html::img($model->img)?>
                    <div class="help-block"></div>
                </div>

    <?= $form->field($model, 'level')->dropDownList(DoctorParentEdit::$levelText) ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
