<?php

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
                                return Html::img($e->image,['class'=>'file-preview-image','style'=>'max-width:300px']);
                            }
                        ],
                    ],
                ]) ?>
                <div class="form-group" style="padding-top: 20px;">
                    <?= Html::a('通过', ['/appoint/done?state=1&referrer='.$referrer.'&id='.$model->id],
                        ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('不通过', ['/appoint/done?state=3&referrer='.$referrer.'&id='.$model->id],
                        ['class' => 'btn btn-danger']) ?>
                </div>
                <?php $form = ActiveForm::begin(); ?>
<?=Html::radioList('state','',[1=>'通过',2=>'不通过'])?>
<div class="form-group">
<?= Html::submitButton($model->isNewRecord ? '提交': '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>