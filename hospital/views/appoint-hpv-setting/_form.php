<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AppointHpvSetting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appoint-hpv-setting-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'week1')->textInput() ?>

    <?= $form->field($model, 'week2')->textInput() ?>

    <?= $form->field($model, 'week3')->textInput() ?>

    <?= $form->field($model, 'week4')->textInput() ?>

    <?= $form->field($model, 'week5')->textInput() ?>

    <?= $form->field($model, 'vid')->widget(Select2::class,[
        'name' => 'vid',
        'data' => [
               43=> '双价宫颈癌疫苗（第一剂）',
            50=>'双价宫颈癌疫苗（第二剂）',
            51=>'双价宫颈癌疫苗（第三剂）',
            54=>'四价宫颈癌疫苗（第一剂）',
            55=>'四价宫颈癌疫苗（第二剂）',
            56=>'四价宫颈癌疫苗（第三剂）',
            57=>'九价宫颈癌疫苗（第一剂）',
            58=>'九价宫颈癌疫苗（第二剂）',
            59=>'九价宫颈癌疫苗（第三剂）',
        ],
        'language' => 'de',
        'options' => ['placeholder' => '请选择', 'multiple' => 'multiple'],
        'showToggleAll' => false,
        'value' =>$hospitalid,
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>
    

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
