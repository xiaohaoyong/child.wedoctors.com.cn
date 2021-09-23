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

        <?= $form->field($model, 'vid')->dropDownList([
            ''=> '请选择',
               43=> '双价宫颈癌疫苗（第一剂）',
            50=>'双价宫颈癌疫苗（第二剂）',
            51=>'双价宫颈癌疫苗（第三剂）',
            54=>'四价宫颈癌疫苗（第一剂）',
            55=>'四价宫颈癌疫苗（第二剂）',
            56=>'四价宫颈癌疫苗（第三剂）',
            57=>'九价宫颈癌疫苗（第一剂）',
            58=>'九价宫颈癌疫苗（第二剂）',
            59=>'九价宫颈癌疫苗（第三剂）',
        ]) ?>

        <?= $form->field($model, 'img')->fileInput() ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>

