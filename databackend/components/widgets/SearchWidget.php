<?php
namespace databackend\components\widgets;
use yii\base\InvalidValueException;

/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2016/9/18
 * Time: 17:58
 */
class SearchWidget extends \yii\base\Widget
{

    public $model;
    /**
     * $attribute=[
     *  ['id',[\yii\db\ActiveQuery],default] select
     *  ['name',string,default]  input
     * ]
     */
    public $attribute;
    public function init()
    {

    }
    public function run()
    {
        if(method_exists($this->model,'searchAttr'))
        {
            $attribute=$this->model->searchAttr();

        }elseif($this->attribute) {
            $attribute=$this->attribute;
        }else{
            throw new InvalidValueException("searchAttr 方法未定义");
        }
        if(is_array($attribute))
        {
            echo  $this->render('SearchWidget',['model'=>$this->model,'attribute'=>$attribute]);
        }else{
            throw new InvalidValueException("attribute 必须为数组");
        }
    }
}