<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/12/6
 * Time: 下午4:18
 */

namespace hospital\models;


use yii\base\Model;

class Data extends Model
{
    public $file;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file'], 'required'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'file' => '数据文件',
        ];
    }

}