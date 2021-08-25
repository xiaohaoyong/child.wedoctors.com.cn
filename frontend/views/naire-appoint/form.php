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
$form = ActiveForm::begin([
]); ?>

<div style="text-align: center;line-height:50 px;font-size: 16px;height: 100px;padding: 20px;"><?= $qn->title ?></div>
<?php foreach ($qna as $k => $v) {

    $field = \common\models\QuestionNaireAsk::$fieldText[$v->field];
    $field = $field ? $field : 'answer';
    ?>
    <div style="padding: 0 20px;">
        <div><?= $v->content ?></div>
        <?php if ($v->type == 1) {
            if($v->field==2){
                $ajax=['enableAjaxValidation' => true];
            }else{
                $ajax=[];
            }
            echo $form->field($qnaa, $field . '[' . $v->id . ']',$ajax)->textInput()->label(false);
        } elseif ($v->type == 2) {

            echo $form->field($qnaa, 'answer[' . $v->id . ']')->radioList([0 => '否', 1 => '是'])->label(false);
        } elseif ($v->type == 4) {
            echo $form->field($qnaa, $field . '[' . $v->id . ']')->radioList([1 => '男', 2 => '女'])->label(false);
        } elseif ($v->type == 3) {
            for ($i = 0; $i <= 120; $i++) {
                $year[date('Y', strtotime("-$i year"))] = date('Y', strtotime("-$i year"));
            }
            $month[] = '<option value="">--月--</option>';
            foreach (range(1, 12) as $val) {
                $month[] = '<option value="' . $val . '">' . $val . '</option>';
            }
            $dayNum = [29, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            echo "<script> var days=Array;";
            foreach ($dayNum as $k => $val) {
                echo "days[" . ($k) . "]='<option value=\"\">--日--</option>';";
                for ($i = 1; $i <= $val; $i++) {
                    echo "days[" . ($k) . "]+=\"<option value='" . "$i" . "'>" . $i . '</option>";';
                }
            }
            echo "</script>";


            ?>
            <div style="display: flex;">
                <?= $form->field($qnaa, 'year')->dropDownList($year,
                    [
                        'prompt' => '--年--',
                        'onchange' => "
                            window.year= $(this).val();
                            $('#questionnaireanswer-month').html('" . implode('', $month) . "');
                            $('#questionnaireanswer-day').html('<option value=\"\">--日--</option>');

                        ",
                    ])->label(false) ?>
                <?= $form->field($qnaa, 'month')->dropDownList([],
                    [
                        'prompt' => '--月--',
                        'onchange' => "
                            window.month= $(this).val();
                            var montha=month;
                            console.log(year);
                            if (month==2 && (year % 4 == 0 && year % 100 != 0 || year % 400 == 0)) {
                                montha = 0;
                            }
                            $('#questionnaireanswer-day').html(days[montha]);

                        ",
                    ])->label(false) ?>
                <?= $form->field($qnaa, 'day')->dropDownList([],
                    [
                        'prompt' => '--日--',
                        'onchange' => "
                            var day=$(this).val();
                            $('#questionnaireanswer-date-34').val(year+'-'+month+'-'+day);
                        "
                    ])->label(false) ?>
            </div>
            <?php
            echo $form->field($qnaa, $field . '[' . $v->id . ']')->hiddenInput()->label(false);
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
<?php ActiveForm::end();?>
<div class="appoint_my"><a href="/naire-appoint/my"><img src="/img/appoint_my.png" width="56" height="56"></a></div>

