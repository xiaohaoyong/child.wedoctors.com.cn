<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/1/25
 * Time: 下午3:06
 */
use kartik\file\FileInput;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = "家医团队数据同步";
?>
<div class="child-info-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">

                        <!-- Profile Image -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">上传文件（.xlsx)</h3>
                            </div>
                            <div class="box-body box-profile">
                            <?php
                                $form = ActiveForm::begin([
                                    'action'=>'/doctor-team/data-ex',
                                    'id'=>'form',
                                    'method'=>'post',
                                    'options'=>['enctype'=>'multipart/form-data'],
                                ]); ?>
                                <div class="form-group field-article-subject required ">
                                <label class="control-label" for="article-subject">选择团队</label>
                                    <?=Html::dropDownList('teamid',null,\common\models\DoctorTeam::find()->select('title')->indexBy('id')->where(['doctorid'=>$doctorid])->column(), ['prompt'=>'请选择团队','class'=>'form-control','id'=>'teamid'])?>
                                </div>
                                <div class="form-group field-article-subject required">
                                <label class="control-label" for="article-subject">上传图片</label>

                               <input type="file" name="team-file">
                                </div>
                                <div class="form-group field-article-subject required">

                                <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :'btn btn-primary']) ?>
                                </div>
                            <?php ActiveForm::end(); ?>

                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->

                        <!-- About Me Box -->
                        
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-8">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#ing" data-toggle="tab">家医团队</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="active tab-pane" id="ing">
                                    <table id="example2" class="table table-bordered table-hover"
                                           style="font-size: 12px;">
                                        <thead>
                                        <tr>
                                            <th>团队名称</th>
                                            <th>管辖儿童数</th>
                                            
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($teams as $k => $v) {
                                            ?>
                                            <tr>
                                                <td><?= $v->title ?></td>
                                                <td>1</td>
                                                
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div>
                        <!-- /.nav-tabs-custom -->
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>