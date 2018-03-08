<?php
$this->title = "分配权限 ";
$query =\app\models\rbac\AuthMenu::find()->select(['id', 'name', 'description', 'sort', 'display','parent_id']);
$list = $query->orderBy('sort')->asArray()->all();
foreach($list as $k=>$v)
{
    $rs=[];
    $rs['id']=$v['id'];
    $rs['value']=$v['name']; //自定义隐藏域内容
    $rs['parent']=$v['parent_id']?$v['parent_id']:"#";
    $rs['text']=$v['description'];
    if(in_array($v['name'],$permissions)){
        $rs['state']["selected"]=true;
        $rs['state']["opened"]=true;
    }elseif(intval($v['parent_id']))
    {
        $rs["icon"]= "fa fa-check fa-ellipsis-v";
    }
    $data[]=$rs;
}
//echo json_encode($data);exit;
//shuffle($data);
echo \common\widgets\JstreeWidget::widget(
    [
        'id'=>'jstree',
        'field'=>'childs',
        'action'=>\Yii::$app->urlManager->createUrl(['rbac/add-child']),
        'clientOptions'=>[
            'plugins' => ['checkbox'],
            'core' =>
                ['data' =>$data],
        ]
    ]
);
?>
<div id="jstree"></div>