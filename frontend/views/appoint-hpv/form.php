<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Appoint */

$this->title = 'HPV疫苗接种申请';
?>
<div class="appoint-create appoint">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="appoint-form">

        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'doctorid')->hiddenInput(['value'=>$doctorid])->label(false) ?>
        <div class="form-group field-appointhpv-nam">
            <label class="control-label">预约社区</label>
            <?=\common\models\Hospital::findOne(['id'=>\common\models\UserDoctor::findOne(['userid'=>$doctorid])->hospitalid])->name?>
        </div>
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'phone')->textInput() ?>
        <?= $form->field($model, 'idcard')->textInput() ?>
        <?= $form->field($model, 'address')->textInput() ?>

        <?= $form->field($model, 'vid')->dropDownList([
            ''=> '请选择']+$vids) ?>

        <?= $form->field($model, 'img')->fileInput() ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
<div class="appoint_my"><a href="/appoint-hpv/my"><img src="/img/appoint_my.png" width="56" height="56"></a></div>


