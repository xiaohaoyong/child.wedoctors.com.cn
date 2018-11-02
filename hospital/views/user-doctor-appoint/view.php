<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\UserDoctorAppoint */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => 'User Doctor Appoints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-doctor-appoint-view">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <p>
                    <?= Html::a('修改', ['update', 'id' => $model->doctorid],
                        ['class' => 'btn btn-primary']) ?>
                </p>

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'weeks',
                        'cycle',
                        'delay',
                        'type1_num',
                        'type2_num',
                        'type3_num',
                        'type4_num',
                        'type5_num',
                        'type6_num',
                    ],
                ]) ?>

            </div>
        </div>
    </div>
</div>