<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '管理员';
\common\helpers\HeaderActionHelper::$action = [
    ['url' => ['adminuser/index'], 'name' => "管理员列表"],
    ['url' => ['adminuser/add'], 'name' => "添加管理员"],
];
?>

<?php
$form = ActiveForm::begin([
            'options' => [
                'class' => 'form-horizontal',
            ],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-lg-5\">\n{hint}\n{error}</div>",
                'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
        ]);
?>
<?= $form->field($model, 'name')->textInput(['value' => $row->name]) ?>
<?= $form->field($model, 'userlogin')->textInput(['value' => $row->userlogin]) ?>
<?= $form->field($model, 'password')->textInput(['value' => $row->password])->passwordInput(['value' => $row->password]) ?>
<?php if (\Yii::$app->controller->action->id == 'update'): ?>
    <?= $form->field($model, 'isUpdatePassword')->textInput()->radioList(['1' => '是', '0' => '否'])->label('是否修改密码') ?>
<?php endif; ?>
<?= $form->field($model, 'phone')->textInput(['value' => $row->phone]) ?>
<?= $form->field($model, 'email')->textInput(['value' => $row->email]) ?>
<?= $form->field($model, 'mark')->textInput(['value' => $row->mark]) ?>
<?= $form->field($model, 'status')->textInput()->radioList(['1' => '禁用', '0' => '正常']) ?>
<?= $form->field($model, 'is_admin')->textInput()->radioList(['1' => '是', '0' => '否']) ?>

<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Submit', ['class' => 'btn green']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
