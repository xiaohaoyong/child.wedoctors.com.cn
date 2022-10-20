<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
?>
<style>
    .content{padding-top: 30px;}
    .header{display:flex;justify-content:center;align-items:center;margin:0 auto;height:90px;background: url("/img/touying.png") no-repeat center;background-size: 354px 97px;}

    .zhuangtai{display: flex;flex-direction: column;justify-content: center;align-items: center; margin-top: 40px;}
    body{background-color: #ffffff}
    .view{background:url("/img/qn_view.png") no-repeat;background-size:232px 56px;width: 232px;height: 56px;line-height: 56px; text-align: center;font-size: 18px;color: #ffffff;margin-top: 30px;}
</style>
<div class="content" >

    <div class="zhuangtai">

        <img src="/img/zhuangtai_true.png" width="30">
        <div class="value" style="font-size: 20px; margin-top: 10px;">提交成功</div>
        <div class="info" style="padding: 0 50px;    text-align: justify;
margin-top: 20px;color: #999999;font-size: 16px;">您的申请已提交成功，请耐心等待社区审核。具体接种日期将在审核通过后通过"儿宝宝"微信公众号通知您，请您近期留意</div>
    </div>
    <div class="appoint-hpv-view">
        <div class="col-xs-12">
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'name',
                            'phone',
                            [

                                'attribute' => 'date',
                                'value' => function ($e) {
                                    if(!$e->state)
                                    {
                                        return '待定';
                                    }else{
                                        return $e->date;
                                    }
                                }
                            ],
                            [

                                'attribute' => 'state',
                                'value' => function ($e) {
                                    return \common\models\AppointHpv::$stateText[$e->state];
                                }
                            ],
                            [

                                'attribute' => 'doctorid',
                                'value' => function ($e) {
                                    return \common\models\Hospital::findOne(['id'=>\common\models\UserDoctor::findOne(['userid'=>$e->doctorid])->hospitalid])->name;
                                }
                            ],
                            [

                                'attribute' => 'vid',
                                'value' => function ($e) {
                                    return \common\models\Vaccine::findOne($e->vid)->name;
                                }
                            ],
                            'cratettime:datetime',

                        ],
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>