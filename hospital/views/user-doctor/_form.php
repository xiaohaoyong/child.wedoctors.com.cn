<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $userInfo common\models\UserDoctor */
/* @var $userLogin common\models\userLogin */

/* @var $form yii\widgets\ActiveForm */

?>

<div class="users-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($userInfo,'phone')->textInput()?>
                <?= $form->field($userInfo,'intro')->textarea(['rows'=>5])?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
            <!-- /.box-body -->
        </div>
    </div>


</div>
