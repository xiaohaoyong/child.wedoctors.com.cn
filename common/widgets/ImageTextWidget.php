<?php
namespace databackend\components\widgets;
use yii\base\Widget;
use databackend\assets\ImageShowAsset;
use yii\base\InvalidValueException;

class ImageTextWidget extends Widget
{
    public $model;
    public $attribute;
    public $title;
    public function init()
    {
    }

    public function run()
    {
        if($this->model){
            $id=$this->model->id;
            if($id) {
                $imgText = \Yii::$app->dbmedia->createCommand("select image,content,id from article_image_text  where artid=$id")->queryAll();
            }
            $attr=['model'=>$this->model,'attribute'=>$this->attribute,'title'=>$this->title,'imgtext'=>$imgText];
        }else{
            throw new InvalidValueException("model未定义");
        }

        ImageShowAsset::register($this->getView());
        echo  $this->render('ImageTextWidget',$attr);
    }
}