<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/6/19
 * Time: 下午3:57
 */
?>
<?php $form = ActiveForm::begin(); ?>

<div style="text-align: center;line-height: 100px;font-size: 20px;"><?=$qn->title?></div>
<?php foreach ($qna as $k=>$v){?>
    <div style="padding: 0 20px;">
        <div><?=$v->content?></div>
        <?php if($v->type==1){
            echo $form->field($qnaa,'answer['.$v->id.']')->textInput()->label(false);
        }else{
            echo $form->field($qnaa,'answer['.$v->id.']')->radioList([0=>'否',1=>'是'])->label(false);
        }
        ?>
    </div>
    <hr>
<?php }?>



<?php ActiveForm::end(); ?>

