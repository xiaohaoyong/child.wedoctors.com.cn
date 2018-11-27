<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '宣教任务';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    label{
        display: block;
    }
</style>
<div class="article-index">

    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php
                $model=new \common\models\ArticleSend();
                $article=\common\models\Article::find()
                    ->select('article_info.title')->indexBy('id')
                    ->leftJoin('article_info', '`article_info`.`id` = `article`.`id`')
                    ->where(['article.child_type'=>$type,'article.type'=>1])->column();
                $article2=\common\models\Article::find()
                    ->select('article_info.title')->indexBy('id')
                    ->leftJoin('article_info', '`article_info`.`id` = `article`.`id`')
                    ->where(['article.child_type'=>$type,'article.type'=>0])->column();
                $form =ActiveForm::begin(); ?>

                <table id="w0" class="table table-striped table-bordered detail-view">
                    <tbody>
                        <tr><th>宣教发送基本内容</th><th>可选宣教内容</th></tr>
                        <tr><td>
                                <?php foreach($article as $k=>$v){?>
                                    <?=$v."--".Html::a('查看详情',['article/view','id'=>$k])?><br>
                                <?php }?>
                            </td><td>
                                <?php foreach($article2 as $k=>$v){?>
                                    <?=Html::checkbox('artid[]',false,['value'=>$k,'label'=>$v."--".Html::a('查看详情',['article/view','id'=>$k])])?>
                                <?php }?>
                            </td></tr>
                        <tr><th>被宣教人数</th><th>
                                <?php $doctorid=\common\models\UserDoctor::findOne(['hospitalid'=>Yii::$app->user->identity->hospital])->userid; ?>
                                <?=\common\models\ArticleUser::noSendChildNum($type,$doctorid)?>
                            </th>
                        </tr>
                    </tbody>
                </table>
                <?= Html::submitButton("提交", ['class' => 'btn btn-success']) ?>

                <?php ActiveForm::end(); ?>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
