<?php
namespace databackend\components\widgets;

use yii\base\Widget;
use databackend\assets\TagInputAsset;
use yii\base\InvalidValueException;
/**
 * 标签文本框
 * @author      lixiaosong
 */
class TagInputWidget extends Widget
{
    public $model;
    public $attribute;
    public $label;
    public $value;
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        //加载js

        if($this->model){
          //  var_dump($this->value);exit;
            if($this->value){
               foreach($this->value as $k=>$v){
                   $id[]=$v['id'];
                   $tag[]=$v['tag'];
               }
               $tags=implode(',',$tag);
            }
            $attr=['model'=>$this->model,'attribute'=>$this->attribute,'id'=>$id,'tag'=>$tags];
        }else{
            throw new InvalidValueException("参数未定义");
        }
        TagInputAsset::register($this->getView());
        echo  $this->render('TagInputWidget',$attr);
    }
}