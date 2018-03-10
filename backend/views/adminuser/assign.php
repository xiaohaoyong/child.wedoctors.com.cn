<?php
$this->title = "分配角色";
foreach($list as $k=>$v)
{
    $rs=[];
    $rs['id']=$v->name;
    $rs['value']=$v->name; //自定义隐藏域内容
    $rs['text']=$v->description;
    if(in_array($v->name,$roles)){
        $rs['state']["selected"]=true;
        $rs['state']["opened"]=true;
    }
    $rs["icon"]= "fa icon-users fa-ellipsis-v";

    $data[]=$rs;
}
//echo json_encode($data);exit;
//shuffle($data);
echo \common\widgets\JstreeWidget::widget(
    [
        'id'=>'jstree',
        'field'=>'childs',
        'action'=>\Yii::$app->urlManager->createUrl(['adminuser/assign']),
        'clientOptions'=>[
            'plugins' => ['checkbox','wholerow'],
            'core' =>
                ['data' =>$data],
        ]
    ]
);
?>
<div id="jstree"></div>