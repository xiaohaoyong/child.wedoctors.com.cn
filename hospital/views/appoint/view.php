<?php

use common\models\AppointImg;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Appoint */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => 'Appoints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '列表', 'url' => ['index']],
    1 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="appoint-view">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'attribute' => 'image',
                            'format'=>'raw',
                            'value' => function ($e) {
                                $imgs=AppointImg::findAll(['aid'=>$e->id]);
                                $html = Html::img($e->image,['class'=>'file-preview-image','style'=>'max-width:300px']);
                                foreach($imgs as $k=>$v){
                                    $html.=Html::img($v->img,['class'=>'file-preview-image','style'=>'max-width:300px']);
                                }
                                return $html;
                            }
                        ],
                    ],
                ]) ?>
                <?php $form = ActiveForm::begin([
                    'action' => ['appoint/done']                ]); ?>
<?= $form->field($model,'referrer')->hiddenInput(['value'=>$referrer]) ?>
<?= $form->field($model,'id')->hiddenInput(['value'=>$model->id])->label(false) ?>
<?= $form->field($model,'state')->radioList([1=>'通过',3=>'不通过'])->label(false)?>

<div class="form-group">
<?= Html::submitButton($model->isNewRecord ? '提交': '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>