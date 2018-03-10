<?php

namespace databackend\components\widgets;
use yii\widgets\ActiveForm;
use yii\base\Widget;
use yii\web\Response;
use yii\helpers\Url;
use databackend\assets\ManyPagesAsset;
use yii\base\InvalidValueException;
/**
 * @example 多选下拉框运用
 * Created by PhpStorm.
 * User: Lixiaosong
 * Date: 2016/9/35
 * Time: 17:58
 */
class ManyPagesWidget extends Widget
{
    public $model; //下拉框内容，为一维数组，id为键，内容为值
    public $attribute;
    public $url;
    public $header;
    public $widgets;
    public $style;
    public $prompt;


    public function init()
    {
    }
    public function run()
    {

        ManyPagesAsset::register($this->getView());
        if(empty($this->model)){
            throw new InvalidValueException("model未定义");
        }
        $attr=['model'=>$this->model,'attribute'=>$this->attribute,'url'=>$this->url,
            'header'=>$this->header,'widgets'=>$this->widgets,'style'=>$this->style,'prompt'=>$this->prompt];
        echo  $this->render('ManyPagesWidget',$attr);
    }
}


/*调用方法
<?=\common\widgets\ManyPagesWidget::widget(
    [   'model'=>$model,  //实例化表类
        'url'=>'media/media/add_image' //表单提交地址,
        'header'=>['主体信息','图文'], //头部进度信息
        'attribute'=>[                //提交表单所包含属性
            ['标题'=>'title','标签'=>'tag','图片'=>'image'],
            ['文章内容'=>'content'],
        ],
        'widgets'=>['content'=>'pjkui\kindeditor\KindEditor','image'=>'\common\widgets\ImageShowWidget'] //属性中所用小部件的命名空间对应
    ]
)*/?>