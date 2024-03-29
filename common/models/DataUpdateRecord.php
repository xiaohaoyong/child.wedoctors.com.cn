<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_update_record".
 *
 * @property int $id
 * @property int $createtime
 * @property int $state
 * @property int $type
 * @property int $num
 * @property int $new_num
 * @property int $hospitalid
 */
class DataUpdateRecord extends \yii\db\ActiveRecord
{
    public static $stateText=[
        0=>'上传成功等待处理中',
        1=>'文件处理中',
        2=>'文件解析完成，正在同步数据',
        3=>'数据同步完成',
        4=>'比配失败，未查询到对应表头'
    ];
    public static $typeText=[
        0=>'无',
        1=>'体检',
        2=>'孕期',
        3=>'妇幼',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_update_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createtime', 'state', 'type', 'num', 'new_num', 'hospitalid'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'createtime' => '上传时间',
            'state' => '状态',
            'type' => '类型',
            'num' => '内容数',
            'new_num' => '新增数',
            'hospitalid' => '社区医院',
        ];
    }
    public function beforeSave($insert)
    {
        if($insert){
            $this->createtime=time();
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
