<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model hospital\models\user\ChildInfo */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Child Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="child-info-view">

    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'userid',
                        'name',
                        'birthday:datetime',
                        'createtime:datetime',
                        'gender',
                        'source',
                        'admin',
                        'field54',
                        'field53',
                        'field52',
                        'field51',
                        'field50',
                        'field49',
                        'field48',
                        'field47',
                        'field46',
                        'field45',
                        'field44',
                        'field43',
                        'field42',
                        'field41',
                        'field40',
                        'field39',
                        'field38',
                        'field37',
                        'field27',
                        'field26',
                        'field25',
                        'field24',
                        'field23',
                        'field22',
                        'field21',
                        'field20',
                        'field19',
                        'field18',
                        'field17',
                        'field16',
                        'field15',
                        'field14',
                        'field13',
                        'field7',
                        'field6',
                        'field0',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
