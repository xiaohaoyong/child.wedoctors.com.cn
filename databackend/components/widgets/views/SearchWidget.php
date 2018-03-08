<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div class="post-search">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]);
    foreach($attribute as $k=>$v){
        if($v[1])
        {
            $php='echo $form->field($model, $v[0])';
            foreach($v[1] as $ak=>$av)
            {
                if($ak=='widget' && is_array($av))
                {
                    $php.='->$ak($av[0],$av[1])';
                }else{
                    $php.='->$ak($av)';
                }
            }
            if(!$v[1]['label'])
            {
                $php.='->label(\'\')';
            }
            $php.=";";
        }
        eval($php);
        ?>
    <?php }?>
    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?=Html::a('<span class="btn btn-primary">重置</span>',Url::canonical(),'');?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>
</div>