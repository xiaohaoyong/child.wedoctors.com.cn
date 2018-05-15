    <?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ChildInfoSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '健康档案';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="child-info-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <hr>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            [

                'attribute'=>'性别',
                'value'=>function($e)
                {
                    return \common\models\ChildInfo::$genderText[$e->gender];
                }
            ],
            [
                'attribute'=>'出生日期',
                'value'=>function($e)
                {

                    return $e->birthday?date('Y-m-d',$e->birthday):"";
                }
            ],
            [
                'attribute'=>'母亲姓名',
                'value'=>function($e)
                {
                    return $e->parent->mother;

                }
            ],
            [
                'attribute'=>'母亲电话',
                'value'=>function($e)
                {
                    return $e->parent->mother_phone;

                }
            ],
            [
                'attribute'=>'父亲姓名',
                'value'=>function($e)
                {
                    return $e->parent->father;
                }
            ],
            [
                'attribute'=>'父亲电话',
                'value'=>function($e)
                {
                    return $e->parent->father_phone;

                }
            ],
            [
                'attribute'=>'联系人姓名',
                'value'=>function($e)
                {
                    return $e->parent->field11;

                }
            ],
            [
                'attribute'=>'联系人电话',
                'value'=>function($e)
                {
                    return $e->parent->field12;

                }
            ],
            [
                'attribute'=>'建册机构',
                'value'=>function($e)
                {
                    return $e->field23;

                }
            ],
            [
                'attribute'=>'管理机构',
                'value'=>function($e)
                {
                    return $e->field24;

                }
            ],
            [
                'attribute'=>'居委会',
                'value'=>function($e)
                {
                    return $e->field50;

                }
            ],
            [
                'attribute'=>'签约时间',
                'value'=>function($e)
                {

                    return $e->sign->level==1?date('Y-m-d H:i',$e->sign->createtime):"未签约";
                }
            ],
            [
                'attribute'=>'签约社区',
                'value'=>function($e)
                {
                    return $e->doctor[0]->name?$e->doctor[0]->name:"未签约";
                }
            ],

        ],
    ]); ?>
</div>
