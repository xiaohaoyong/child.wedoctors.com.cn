<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title=$qn->title;

/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/6/19
 * Time: 下午3:57
 */
?>
<?php $form = ActiveForm::begin(); ?>
<fieldset disabled>

    <div style="text-align: center;line-height:50 px;font-size: 16px;height: 100px;padding: 20px;"><?=$qn->title?></div>
<?php foreach ($qna as $k=>$v){

    $field=\common\models\QuestionNaireAsk::$fieldText[$v->field];
    $field=$field?$field:'answer';
    ?>
    <div style="padding: 0 20px;">
        <div><?=$v->content?></div>
        <?php if ($v->type == 1) {
            echo $form->field($qnaa[$v->id], $field . '[' . $v->id . ']')->textInput(['value'=>$qnaa[$v->id]->answer])->label(false);
        } elseif ($v->type == 2) {
            echo $form->field($qnaa[$v->id], 'answer[' . $v->id . ']')->radioList([0 => '否', 1 => '是'],['value'=>$qnaa[$v->id]->answer])->label(false);
        } elseif ($v->type == 4) {
            echo $form->field($qnaa[$v->id], $field . '[' . $v->id . ']')->radioList([1 => '男', 2 => '女'],['value'=>$qnaa[$v->id]->answer])->label(false);
        } elseif ($v->type == 3) {
            echo $form->field($qnaa[$v->id], $field . '[' . $v->id . ']')->textInput(['value'=>$qnaa[$v->id]->answer])->label(false);
        }
        ?>
    </div>
    <hr>
<?php }?>
<div style="padding: 0 20px;">
    请您如实填写此表，鉴于目前疫情特殊期间，如果不能按时就诊，请及时取消号源，感谢您的理解与配合。
</div>
<hr>
</fieldset>
<hr>
<?php ActiveForm::end(); ?>

