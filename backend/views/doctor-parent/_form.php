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

                <hr>

                <?= $form->field($model, 'doctorid')->dropDownList(\common\models\UserDoctor::find()->select('name')->indexBy('userid')->column(), [
        'prompt' => '请选择',
        'onchange' => '
            $("#' . Html::getInputId($model, 'teamid') . '").html(\'' . Html::tag('option', Html::encode("请选择"), array('value' => 0)) . '\');
            $.post("' . \yii\helpers\Url::to(['doctor-team/get']) . '?DoctorTeamSearch[doctorid]="+$(this).val(),function(data){
                $("#' . Html::getInputId($model, 'teamid') . '").html(data);
            });',
    ]) ?>
                <?= $form->field($model, 'teamid')->dropDownList(\common\models\DoctorTeam::find()->select('title')->indexBy('id')->where(['doctorid'=>$model->doctorid])->column(), ['prompt' => '请选择']); ?>



                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                        'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
