<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/12/18
 * Time: 下午2:35
 */
$this->title = '自动发送提醒体检通知';

?>


<div class="examination-automatic-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">

                <?php $form = ActiveForm::begin(); ?>
                <table id="w0" class="table table-striped table-bordered detail-view">
                    <tbody>
                    <tr>
                        <td colspan="2">基本设置</td>
                    </tr>
                    <tr>
                        <td colspan="2"><?= $form->field($model,'level')->checkbox([],false) ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><?= $form->field($model,'end')->checkbox([],false) ?></td>
                    </tr>
                    </tbody>
                </table>
                <table id="w0" class="table table-striped table-bordered detail-view">
                    <tbody>
                    <tr>
                        <td colspan="2">选择推送月龄</td>
                    </tr>
                    <tr>
                        <td>系管年龄</td>
                        <td>选择月份</td>
                    </tr>
                    <tr>
                        <td>2-3月</td>
                        <td><?= $form->field($model,'month1')->radioList([0=>'无',2 => '2月', 3 => '3月'])->label(false) ?></td>
                    </tr>
                    <tr>
                        <td>5-6月</td>
                        <td><?= $form->field($model,'month2')->radioList([0=>'无',5 => '5月', 6 => '6月'])->label(false) ?></td>
                    </tr>
                    <tr>
                        <td>8-9月</td>
                        <td><?= $form->field($model,'month3')->radioList([0=>'无',8 => '8月', 9 => '9月'])->label(false) ?></td>
                    </tr>
                    <tr>
                        <td>11-12月</td>
                        <td><?= $form->field($model,'month4')->radioList([0=>'无',11 => '11月', 12 => '12月'])->label(false) ?></td>
                    </tr>
                    <tr>
                        <td>1岁5月-6月</td>
                        <td><?= $form->field($model,'month5')->radioList([0=>'无',17 => '1岁5月', 18 => '1岁6月'])->label(false) ?></td>
                    </tr>
                    <tr>
                        <td>1岁11月-12月</td>
                        <td><?= $form->field($model,'month6')->radioList([0=>'无',23 => '1岁11月', 24 => '1岁12月'])->label(false) ?></td>
                    </tr>
                    <tr>
                        <td>2岁5月-6月</td>
                        <td><?= $form->field($model,'month7')->radioList([0=>'无',29 => '2岁5月', 30 => '2岁6月'])->label(false) ?></td>
                    </tr>
                    <tr>
                        <td>2岁11月-12月</td>
                        <td><?= $form->field($model,'month8')->radioList([0=>'无',41 => '2岁11月', 42 => '2岁12月'])->label(false) ?></td>
                    </tr>
                    </tbody>
                </table>

                <table id="w0" class="table table-striped table-bordered detail-view">
                    <tbody>
                    <tr>
                        <td colspan="2">注1：每个年龄段按照选择的月份的前一天推送提醒通知<br>如按照本社区安排2-3月体检需儿童满3月龄，则选择3月！系统将会在儿童满3月龄前一天自动推送体检提醒通知
                        </td>
                    </tr>
                    <tr>
                        <td>
                            注2：如果勾选此选项则向按照系管年龄，向到结束月龄未能来体检的家长发送通知
                            <br>如:2-3月 在3月龄最后一周的第一天检测是否存在未体检儿童（按照体检记录）如存在则发送提醒
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">注3：如启用后未选择月份则仅保存设置不推送提醒</td>
                    </tr>
                    <tr>
                        <td colspan="2">注4：该功能设置完成后如不选择《到期提醒》则仅在选择月份的前一天推送，如选择《到期提醒》则在选择月份的前一天和到期的前一周各进行一次检测并推送</td>
                    </tr>

                    </tbody>
                </table>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                        'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
