<?php
namespace common\models;
use yii\elasticsearch\ActiveRecord;
class Merge extends ActiveRecord
{
    //需要返回的字段
    public function attributes()
    {
        return ['content','title']; //其实这里就是你要查询的字段，你要查什么写什么字段就好了
    }
    //索引
    public static function index()
    {
        return 'question';
    }
    //文档类型
    public static function type()
    {
        return 'xinguan';
    }
    //这个就是第二步配置的组件的名字（key值）
    public static function getDb()
    {
        return \Yii::$app->get('elasticsearch');
    }
}