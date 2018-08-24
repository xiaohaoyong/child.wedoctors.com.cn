<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '宣教任务';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="article-index">

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">

                    <div>
                        <h3 class="box-title">设置：</h3>
                        自动宣教：<?= Html::checkbox('name', $ispush, ['id' => 'article_push']) ?>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example2" class="table table-bordered table-hover" style="font-size: 12px;">
                        <thead>
                        <tr>
                            <th>月龄</th>
                            <th>本月未宣教</th>
                            <th>历史已宣教数</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $doctorid = \common\models\UserDoctor::findOne(['hospitalid' => Yii::$app->user->identity->hospital])->userid;
                        foreach (\common\models\Article::$childText as $k => $v) {
                            if ($k) {
                                ?>
                                <tr>
                                    <td><?= $v ?></td>
                                    <td><?= \common\models\ArticleUser::noSendChildNum($k, $doctorid) ?></td>
                                    <td><?= \common\models\ArticleUser::find()->where(['userid' => $doctorid])->andWhere(['child_type' => $k])->count() ?></td>
                                    <td><?= Html::a('宣教', ['article-send/send-view', 'type' => $k]) ?></td>
                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
<?php
$updateJs = '
    jQuery("#article_push").click(function () {
        if(this.checked==true){
            if(confirm("注：\n1，勾选后将由平台每日晚8时对符合条件的用户进行宣教\n2，自动宣教将会携带可选内容")){
               jQuery.get("article-send/ispush?id=1",{},function(e){
               });
            }
        }else{
            jQuery.get("article-send/ispush?id=0",{},function(e){
            });
        }
    });';
$this->registerJs($updateJs);
?>