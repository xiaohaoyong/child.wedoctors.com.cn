<?php
namespace common\widgets;
use yii\base\Widget;
use common\assets\FileuploadAsset;
use yii\base\InvalidValueException;

class FileuploadWidget extends Widget
{
    public $model;
    public $attribute;
    public $title;
    public function init()
    {
    }

    public function run()
    {
        FileuploadAsset::register($this->getView());
        if($this->model){
            $attr=['model'=>$this->model,'attribute'=>$this->attribute,'title'=>$this->title];
        }else{
            throw new InvalidValueException("model未定义");
        }
        echo  $this->render('FileuploadWidget',$attr);
    }
}