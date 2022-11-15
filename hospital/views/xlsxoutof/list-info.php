<?php

use yii\helpers\Html;
use yii\grid\GridView;


use yii\widgets\ActiveForm;



$this->title = '迁入迁出历史';
//$this->params['breadcrumbs'][] = $this->title;
/*
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
define('TTT',$ttt);
*/
?>
<div class="autograph-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">检索：</h3>
                <div>
                    <div class="autograph-search">
						<?php $form = ActiveForm::begin([
                            'action' => ['list-info'],
                            'method' => 'get',
                            'options' => ['class' => 'form-inline'],
                        ]);
                    
                        ?>
                        
                    	<?= $form->field($searchModel, 'file_type_num')->dropDownList(\hospital\models\XlsxoutofInfoSearch::$file_type_num_Text,['prompt'=>'请选择']) ?>
                        
                        <?= $form->field($searchModel, 'userid') ?>
						<?= $form->field($searchModel, 'username') ?>
                        
                        <?= $form->field($searchModel, 'add_time')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autocomplete'=>'off',
                            'todayHighlight' => true
                        ]]) ?>
                        <?= $form->field($searchModel, 'add_time_e')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autocomplete'=>'off',
                            'todayHighlight' => true
                        ]]) ?>
                        <?= $form->field($searchModel, 'type_num')->dropDownList(\hospital\models\XlsxoutofInfoSearch::$type_num_Text,['prompt'=>'请选择']) ?>
                        
                        <div class="form-group">
                            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
                            <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
                            <div class="help-block"></div>
                        </div>
                    
                        <?php ActiveForm::end(); ?>
                    
                    </div>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?= GridView::widget([
                            'options' => ['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,

                            'columns' => [
                                'id',
								[
								 	'attribute' => '用户ID',
                                    'value' => function ($e) {
                                        $return = $e->userid;
                                        return $return;
                                    },
								],
								[
									'attribute' => '姓名',
									'format'=>'html',
                                    'value' => function ($e) {
                                        $return = '';
										if($e->file_type_num==1)		//[1 => '宝宝', 2 => '孕妈'];
										{
											$user_info = \common\models\ChildInfo::find()->select('name')->where(['id' => $e->userid])->one();
										}
										else
										{
											$user_info = \common\models\UserParent::find()->select(['mother'])->where(['userid' => $e->userid])->one();
										}
										
										if( $user_info )
										{
											if($e->file_type_num==1)
											{
												$return = $user_info->name;
											}
											else
											{
												$return = $user_info->mother;
											}
											
										}
                                        return $return;
                                    }
								],
                                [
                                    'attribute' => '用户类型',
                                    'format'=>'html',
                                    'value' => function ($e) {
                                        $return = '';
                                        if( $e->file_type_num==1 )
										{
											$return = '宝宝';
										}
										elseif( $e->file_type_num==2 )
										{
											$return = '孕妈';
										}
                                        return $return;
                                    }
                                ],
								[
                                    'attribute' => '迁入/迁出',
                                    'value' => function ($e) {
                                        $return = '';
                                        if( $e->type_num==1 )
										{
											$return = '迁出';
										}
										else
										{
											$return = '迁入';
										}
                                        return $return;
                                    }
                                ],
								[
									'attribute' => '时间',
									'value' => function ($e) {
										//$sign = \common\models\DoctorParent::findOne(['parentid' => $e->userid, 'level' => 1]);
										
										return  $e->add_time ? date('Y-m-d H:i', $e->add_time) : " ";
									}
								],
								[
									'attribute' => '迁入社区',
									'value' => function ($e) {
										$Hospital_row = \common\models\Hospital::findOne(['id' => $e->in_hospitalid]);
										return  $Hospital_row->name ;
									}
								],
								[
									'attribute' => '迁出社区',
									'value' => function ($e) {
										$Hospital_row = \common\models\Hospital::findOne(['id' => $e->out_hospitalid]);
										return  $Hospital_row->name ;
									}
								],
								/*[
									'attribute' => '操作人',
									'value' => function ($e) {
										$Hospital_row = \common\models\Hospital::findOne(['id' => $e->hospitalid]);
										return $Hospital_row->name;
									}
								]*/
                                
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>