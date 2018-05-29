<?php

use yii\helpers\Html;
use yii\grid\GridView;
use hospital\models\user\UserDoctor;
use hospital\models\user\DoctorParent;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $chile_type */

$this->title = '管理数据';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [0 => ['name' => '添加', 'url' => ['create']]];
?>
<div class="hospital-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',
            [
                'attribute'=>'医生人数',
                'value'=>function($e)
                {
                    return UserDoctor::find()->where(['hospitalid'=>$e->id])->count();
                }
            ],
            [
                'attribute'=>'儿童人数',
                'value'=>function($e)
                {
                    global $chile_type;
                    $doctors=UserDoctor::find()->where(['hospitalid'=>$e->id])->column();
                    if($doctors) {
                        $doctorParent = DoctorParent::find()->select('parentid')->andFilterWhere(['in', 'doctorid', array_values($doctors)])->column();
                        $chile = \common\models\ChildInfo::find()
                            ->where(['in', 'userid', array_values($doctorParent)]);
                        if(\hospital\models\user\Hospital::$chile_type_static){

                            //获取年龄范围
                            $mouth= \common\models\ChildInfo::getChildType(\hospital\models\user\Hospital::$chile_type_static);

                            $chile->andFilterWhere(['>','birthday',$mouth['firstday']])
                                ->andFilterWhere(['<','birthday',$mouth['lastday']])
                                ->all();
                        }
                        $chileNum=$chile->count();
                    }
                    return $chileNum?$chileNum:0;
                }
            ],
            [
                'attribute'=>'宣教次数',
                'value'=>function($e)
                {
                    $doctors=UserDoctor::find()->where(['hospitalid'=>$e->id])->column();
                    if($doctors) {
                        $articleNum = \common\models\ArticleUser::find()->andFilterWhere(['in', 'userid', array_values($doctors)])->count();
                    }
                    return $articleNum?$articleNum:0;
                }
            ],


        ],]); ?>
</div>
