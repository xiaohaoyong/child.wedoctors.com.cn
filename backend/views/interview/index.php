<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\InterviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="interview-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">检索：</h3>
                <div>
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row ">
                        <?= GridView::widget([
                            'options' => ['class' => 'col-sm-12 table text-nowrap'],
                            'dataProvider' => $dataProvider,

                            'columns' => [
                                [
                                    'attribute' => '建档日期',
                                    'value' => function ($e) {
                                        $preg=$e->preg;
                                        return $preg->field5?date('Y-m-d',$preg->field5):"";
                                    }
                                ],
                                [
                                    'attribute' => '孕妇姓名',
                                    'value' => function ($e) {
                                        $preg=$e->preg;
                                        return $preg->field1?$preg->field1:"";
                                    }
                                ],
                                [
                                    'attribute' => '丈夫姓名',
                                    'value' => function ($e) {
                                        $preg=$e->preg;
                                        return $preg?$preg->field36:"";
                                    }
                                ],
                                [
                                    'attribute' => '联系方式',
                                    'value' => function ($e) {
                                        $preg=$e->preg;
                                        return $preg?$preg->field6:"";
                                    }
                                ],

                                [
                                    'attribute' => '丈夫联系方式',
                                    'value' => function ($e) {
                                        $preg=$e->preg;
                                        return $preg?$preg->field38:"";
                                    }
                                ],
                                [
                                    'attribute' => '户籍',
                                    'value' => function ($e) {
                                        $preg=$e->preg;
                                        return $preg?\common\models\Area::$province[$preg->field7]:"";
                                    }
                                ],
                                [
                                    'attribute' => '预产期',
                                    'value' => function ($e) {
                                        $preg=$e->preg;
                                        return $preg->field15?date('Y-m-d',$preg->field15):"";
                                    }
                                ],
                                [
                                    'attribute' => '家庭住址',
                                    'value' => function ($e) {
                                        $preg=$e->preg;
                                        return $preg?$preg->field10:"";
                                    }
                                ],
                                [
                                    'attribute' => '高危情况',
                                    'value' => function ($e) {
                                        $preg=$e->preg;
                                        return $preg?$preg->field79:"";
                                    }
                                ],
                                [
                                    'attribute' => '第一次追访',
                                    'value' => function ($e) {
                                        $inter=\common\models\Interview::findOne(['week'=>1,'userid'=>$e->userid]);
                                        return $inter?date('Y-m-d',$inter->createtime):null;
                                    }
                                ],
                                [
                                    'attribute' => '结果',
                                    'format'=>'html',
                                    'value' => function ($e) {
                                        $inter=\common\models\Interview::findOne(['week'=>1,'userid'=>$e->userid]);
                                        return $inter->info;
                                    }
                                ],
                                [
                                    'attribute' => '第二次追访',
                                    'value' => function ($e) {
                                        $inter=\common\models\Interview::findOne(['week'=>2,'userid'=>$e->userid]);
                                        return $inter?date('Y-m-d',$inter->createtime):null;
                                    }
                                ],
                                [
                                    'attribute' => '结果',
                                    'format'=>'html',
                                    'value' => function ($e) {
                                        $inter=\common\models\Interview::findOne(['week'=>2,'userid'=>$e->userid]);
                                        return $inter->info;
                                    }
                                ],
                                [
                                    'attribute' => '第三次追访',
                                    'value' => function ($e) {
                                        $inter=\common\models\Interview::findOne(['week'=>3,'userid'=>$e->userid]);
                                        return $inter?date('Y-m-d',$inter->createtime):null;
                                    }
                                ],
                                [
                                    'attribute' => '结果',
                                    'format'=>'html',
                                    'value' => function ($e) {
                                        $inter=\common\models\Interview::findOne(['week'=>3,'userid'=>$e->userid]);
                                        return $inter->info;
                                    }
                                ],
                                [
                                    'attribute' => '第四次追访',
                                    'value' => function ($e) {
                                        $inter=\common\models\Interview::findOne(['week'=>4,'userid'=>$e->userid]);
                                        return $inter?date('Y-m-d',$inter->createtime):null;
                                    }
                                ],
                                [
                                    'attribute' => '结果',
                                    'format'=>'html',
                                    'value' => function ($e) {
                                        $inter=\common\models\Interview::findOne(['week'=>4,'userid'=>$e->userid]);
                                        return $inter->info;
                                    }
                                ],
                                [
                                    'attribute' => '第五次追访',
                                    'value' => function ($e) {
                                        $inter=\common\models\Interview::findOne(['week'=>5,'userid'=>$e->userid]);
                                        return $inter?date('Y-m-d',$inter->createtime):null;
                                    }
                                ],
                                [
                                    'attribute' => '结果',
                                    'format'=>'html',
                                    'value' => function ($e) {
                                        $inter=\common\models\Interview::findOne(['week'=>5,'userid'=>$e->userid]);
                                        return $inter->info;
                                    }
                                ],
                                [
                                    'attribute' => '分娩情况',
                                    'format'=>'html',
                                    'value' => function ($e) {
                                        $inter=\common\models\Interview::findOne(['prenatal'=>2,'userid'=>$e->userid]);
                                        if($inter){
                                            return date('Y-m-d',$inter->childbirth_date)."<br>".$inter->childbirth_hospital;
                                        }
                                    }
                                ],
                                [
                                    'attribute' => '并发症',
                                    'value' => function ($e) {
                                        return '';
                                    }
                                ],
                            ]
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>