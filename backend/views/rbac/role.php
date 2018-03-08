<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '角色';

\common\helpers\HeaderActionHelper::$action = [
    ['url' => ['rbac/roles'], 'name' => "角色列表"],
    ['url' => ['rbac/add-role'], 'name' => "角色添加"],
];
?>

<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'name')->textInput(['value'=>!empty($row['name'])? $row['name'] : '']) ?>
<?= $form->field($model, 'description')->textInput(['value'=>!empty($row['description'])? $row['description'] : '']) ?>


<input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>" />
<?= Html::submitButton('Submit') ?>
<?php ActiveForm::end(); ?>
