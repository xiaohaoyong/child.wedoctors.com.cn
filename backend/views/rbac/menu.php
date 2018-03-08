<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = "菜单";
\common\helpers\HeaderActionHelper::$action = [
    ['url' => ['rbac/menu-list'], 'name' => "菜单列表"],
    ['url' => ['rbac/add-menu'], 'name' => "菜单添加"],
];
?>

<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'moduleId')->textInput(['value' => !empty($row['moduleId']) ? $row['moduleId'] : '']) ?>
<?= $form->field($model, 'controllerId')->textInput(['value' => !empty($row['controllerId']) ? $row['controllerId'] : '']) ?>
<?= $form->field($model, 'actionId')->textInput(['value' => !empty($row['actionId']) ? $row['actionId'] : '']) ?>
<?= $form->field($model, 'sort')->textInput(['value' => !empty($row['sort']) ? $row['sort'] : 0]) ?>
<?= $form->field($model, 'display')->textInput()->radioList(['1'=>'显示', '0'=>'不显示']) ?>
<?= $form->field($model, 'parent_id')->textInput()->dropDownList($menus) ?>
<?= $form->field($model, 'description')->textInput(['value' => !empty($row['description']) ? $row['description'] : '']) ?>
<?= $form->field($model, 'icon')->textInput(['value' => !empty($row['icon']) ? $row['icon'] : '']) ?>

<input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>" />
<?= Html::submitButton('Submit') ?>
<?php ActiveForm::end(); ?>
