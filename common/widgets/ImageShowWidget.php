<?php
namespace common\widgets;
use yii\base\Widget;
use common\assets\ImageShowAsset;
use yii\base\InvalidValueException;

class ImageShowWidget extends Widget
{
    public $model;
    public $attribute;
    public $title;
    public function init()
    {
     }

    public function run()
    {
        ImageShowAsset::register($this->getView());
         $id=$this->model['id'];
        if($id) {
            $images = \Yii::$app->dbmedia->createCommand("select image,id from article_image  where artid=:id",[':id'=>$id])->queryAll();
            $cont=\Yii::$app->dbmedia->createCommand("select content from article_text  where artid=:id",[':id'=>$id])->queryOne();
        }
        if($this->model){
            $attr=['model'=>$this->model,'attribute'=>$this->attribute,'title'=>$this->title,'images'=>$images,'content'=>$cont];
        }else{
            throw new InvalidValueException("model未定义");
        }
        echo  $this->render('ImageShowWidget',$attr);
    }
}