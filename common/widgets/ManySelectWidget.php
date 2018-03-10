<?php

namespace common\widgets;
use yii\base\Widget;
use common\assets\ManySelectAsset;
use yii\base\InvalidValueException;
/**
 * @example 多选下拉框运用
 * Created by PhpStorm.
 * User: Lixiaosong
 * Date: 2016/9/35
 * Time: 17:58
 */
class ManySelectWidget extends Widget
{
    public $select; //下拉框内容，为一维数组，id为键，内容为值
    public $model; //下拉框内容，为一维数组，id为键，内容为值
    public $selected; //已选中内容，为一维数组，值为id
    public $size;  //规定选中的个数
    public $attribute;  //规定选中的个数
    public function init()
    {
    }
    public function run()
    {
        ManySelectAsset::register($this->getView());
        if(is_array($this->select)){
        $attr=['select'=>$this->select,'selected'=>$this->selected,'size'=>$this->size?$this->size:0,'attribute'=>$this->attribute];
        }else{
            throw new InvalidValueException("select必须为数组");
        }
        echo  $this->render('ManySelectWidget',$attr);
    }



}