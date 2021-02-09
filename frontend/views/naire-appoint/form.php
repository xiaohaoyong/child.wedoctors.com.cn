<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $qn->title;

/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/6/19
 * Time: 下午3:57
 */
?>
<?php $form = ActiveForm::begin([
]); ?>

<div style="text-align: center;line-height:50 px;font-size: 16px;height: 100px;padding: 20px;"><?= $qn->title ?></div>
<?php foreach ($qna as $k => $v) {

    $field = \common\models\QuestionNaireAsk::$fieldText[$v->field];
    $field = $field ? $field : 'answer';
    ?>
    <div style="padding: 0 20px;">
        <div><?= $v->content ?></div>
        <?php if ($v->type == 1) {
            echo $form->field($qnaa, $field . '[' . $v->id . ']')->textInput()->label(false);
        } elseif ($v->type == 2) {
            echo $form->field($qnaa, 'answer[' . $v->id . ']')->radioList([0 => '否', 1 => '是'])->label(false);
        } elseif ($v->type == 4) {
            echo $form->field($qnaa, $field.'[' . $v->id . ']')->radioList([1 => '男', 2 => '女'])->label(false);
        } elseif ($v->type == 3) {
            echo $form->field($qnaa, $field.'[' . $v->id . ']')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'autocomplete' => 'off',
                'todayHighlight' => true
            ]])->label(false);
        }
        ?>
    </div>
    <hr>
<?php } ?>
<div style="padding: 0 20px;">
    《传染病防治法》规定隐瞒疫区旅游史接触史者要承担相应法律责任，谢谢您的合作
</div>
<hr>
<div style="padding: 0 20px;">
    <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<hr>
<?php ActiveForm::end(); ?>
