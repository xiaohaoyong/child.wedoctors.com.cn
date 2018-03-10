<?php
use yii\widgets\ActiveForm;

?>
<div class="row">
    <!--<div class="col-md-12">-->
    <div class="portlet box green" id="form_wizard_1">
        <div class="portlet-title">
            <div class="caption">
                <!--  <i class="fa fa-gift"></i></span>-->
            </div>
        </div>
        <div class="portlet-body form">
            <?php $form = ActiveForm::begin([
                'action' => [$url],
                'method' => 'post',
                'enableClientValidation'=>false,
                // 'validateOnType'=>true,
                'validateOnChange'=>false,
                'fieldConfig' => [
                    'labelOptions' => ['class' => 'col-lg-2 control-label'],
                ],
                'options' => ['class' => 'form-horizontal t-margin20r', 'id' => 'fileupload',  'enctype' => "multipart/form-data"],
            ]); ?>
            <div class="form-wizard">
                <div class="form-body">
                    <ul class="nav nav-pills nav-justified steps">
                        <?php foreach ($header as $k => $v) { ?>
                            <li>
                                <a href="#tab<?= $k + 1 ?>" data-toggle="tab" class="step">
                                    <span class="number"><?= $k + 1 ?></span>
                                    <span class="desc">
												<i class="fa fa-check"></i><?= $v ?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <li class=<?= $id ? 'active' : 'step' ?>" >
                                    <a href=" #tab<?= $k + 2 ?>" data-toggle="tab" class="step"><span
                            class="number"><?= $k + 2 ?></span>
                        <span class="desc"><i class="fa fa-check"></i> 确认 </span>
                        </a>
                        </li>
                    </ul>


                    <div id="bar" class="progress progress-striped" role="progressbar">
                        <div class="progress-bar progress-bar-success">
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="alert alert-danger display-none">
                            <button class="close" data-dismiss="alert"></button>
                            输入有误，请检查是否正确
                        </div>
                        <div class="display-none" id="draft1">
                            <button class="close" data-dismiss="alert"></button>
                            已经自动保存到草稿箱
                        </div>
                        <div class="alert alert-success display-none" >
                            <button class="close" data-dismiss="alert"></button>
                            验证成功
                        </div>
                        <?php
                        foreach ($attribute as $ks => $vs) { ?>
                            <div class="tab-pane" id="tab<?= $ks + 1 ?>">
                                <?php foreach ($vs as $kks => $vvs) {
                                    $style1 = $style[$vvs][1] ? $style[$vvs][1] : [];
                                    $style2 = $style[$vvs][2] ? $style[$vvs][2] : [];
                                    $hint=$prompt[$vvs];
                                    if (in_array($vvs, array_flip($widgets))) {
                                        echo $form->field($model, $vvs, ['template' => "{label}\n<div class='col-lg-9 tabs<?=$ks+1?>'>{input}\n$hint\n{error}</div>"])->widget($widgets[$vvs],$style1)->label($kks);
                                    } else {
                                        $tb = 'tb' . ($ks + 1);
                                        switch ($style[$vvs][0]) {
                                            case 'dropDownList':
                                                echo $form->field($model, $vvs, ['template' => "{label}\n<div class='col-lg-9 $tb'>{input}\n$hint\n{error}</div>"])->dropDownList($style1, $style2)->label($kks);
                                                break;
                                            case 'textarea' :
                                                echo $form->field($model, $vvs, ['template' => "{label}\n<div class='col-lg-9'>{input}\n$hint\n{error}</div>"])->textarea($style1, $style2)->label($kks);
                                                break;
                                            case 'radioList' :
                                                echo $form->field($model, $vvs, ['template' => "{label}\n<div class='col-lg-9'>{input}\n$hint\n{error}</div>"])->radioList($style1, $style2)->label($kks);
                                                break;
                                            case 'checkbox' :
                                                echo $form->field($model, $vvs, ['template' => "{label}\n<div class='col-lg-9 '>{input}\n$hint\n{error}</div>"])->checkbox($style1, $style2)->label($kks);
                                                break;
                                            case 'checkboxList' :
                                                echo $form->field($model, $vvs, ['template' => "{label}\n<div class='col-lg-9 '>{input}\n$hint\n{error}</div>"])->checkboxList($style1, $style2)->label($kks);
                                                break;
                                            case 'textInput' :
                                                echo $form->field($model, $vvs, ['template' => "{label}\n<div class='col-lg-9 '>{input}$hint\n{error}</div>"])->textInput($style1)->label($kks);
                                                break;
                                            case 'hiddenInput' :
                                                echo $form->field($model, $vvs, ['template' => "{label}\n<div class='col-lg-2 '>{input}\n$hint\n{error}</div>"])->hiddenInput($style1)->label('');
                                                break;
                                            default:
                                                echo $form->field($model, $vvs, ['template' => "{label}\n<div class='col-lg-8 '>{input}\n$hint\n{error}</div>"])->textInput(['style' => 'width:250px'])->label($kks);
                                                break;
                                        }
                                    }
                                    ?>
                                <?php } ?>
                            </div>
                        <?php } ?>


                        <div class="tab-pane <?php if ($id) { ?>active<?php } ?> tabs" id="tab<?= $ks + 2 ?>">
                            <?php
                            $obj = explode('\\', get_class($model));
                            $models = $obj[count($obj) - 1];
                            foreach ($attribute as $k1 => $v1) { ?>
                                <h6 class="form-section text-center" style="color:#26a69a"><strong><?= $header[$k1] ?></strong></h6>
                                <?php
                                foreach ($v1 as $k2 => $v2) {
                                    ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?= $style[$v2][0]=='hiddenInput'?'':$k2 ?></label>
                                        <div class="col-md-6">
                                            <p class="form-control-static" id="<?=$v2.'-'.$models?>" data-display="<?= $models . "[$v2]" ?>">
                                            </p>
                                        </div>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions" >
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9" >
                            <a href="javascript:;" class="btn default button-previous">返回<i class="m-icon-swapleft"></i></a>
                            <a href="javascript:;" class="btn blue button-next">下一步<i class="m-icon-swapright m-icon-white"></i></a>
                            <button type="submit" class="btn blue  button-submit ">提交</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <!--   </div>-->
</div>

<?php $this->endBody();
$jsform="
Metronic.init(); // init metronic core components
Layout.init(); // init current layout
QuickSidebar.init(); // init quick sidebar
Demo.init(); // init demo features
  FormWizard.init();
    FormSamples.init();
   ComponentsFormTools.init();
";
$js[]=$jsform;
$this->registerJs(implode("\n",$js));
?>