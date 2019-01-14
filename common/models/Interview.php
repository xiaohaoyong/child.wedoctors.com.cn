<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "interview".
 *
 * @property int $id
 * @property int $prenatal_test 是否产检
 * @property string $pt_hospital 产检医院
 * @property int $pt_date 产检日期
 * @property int $prenatal 产检情况
 * @property string $childbirth_hospital 分娩医院
 * @property int $childbirth_date 分娩日期
 * @property int $createtime 数据创建时间
 * @property int $pt_value
 * @property int $userid
 * @property int $week 孕周
 */
class Interview extends \yii\db\ActiveRecord
{
    public static $prenatal_Text=[1=>'是',0=>'否'];
    public static $prenatalText=[1=>'正常',0=>'不正常',2=>'已分娩'];
    public static $prenatalValueText=[
        1=>'自然流产',
        2=>'人工流产',
        3=>'胎停育',
        4=>'贫血',
        5=>'妊娠合并糖尿病',
        6=>'甲减',
        7=>'妊娠合并高血压',
        8=>'畸形',
        9=>'其它体检不正常',
    ];

    public static $weekText=[
        1=>'孕12周',
        2=>'孕16-20周',
        3=>'孕21-24周',
        4=>'孕28-36周',
        5=>'孕37-40周',
    ];
    public static $weekidText=[
        1=>12,
        2=>16,
        3=>21,
        4=>28,
        5=>37,
    ];


    public static function getWeek($week){
        if($week<13){
            return 1;
        }elseif($week>=17 && $week<=19){
            return 2;
        }elseif($week>=20 && $week<=23){
            return 3;
        }elseif($week>=29 && $week<=35){
            return 4;
        }elseif($week>=38) {
            return 5;
        }
        return 0;
    }



    public function getCdate(){
        return date('Y-m-d',$this->childbirth_date);
    }


    public function getPdate(){
        return date('Y-m-d',$this->pt_date);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'interview';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prenatal_test', 'pt_date', 'prenatal', 'childbirth_date', 'createtime','userid','pt_value','week'], 'integer'],
            [['pt_hospital', 'childbirth_hospital'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prenatal_test' => '是否产检',
            'pt_hospital' => '产检医院',
            'pt_date' => '产检日期',
            'prenatal' => '产检情况',
            'childbirth_hospital' => '分娩医院',
            'childbirth_date' => '分娩日期',
            'createtime' => '数据创建时间',
            'userid'=>'用户ID',
            'week'=>'用户ID',
            'pt_value'=>'体检内容'
        ];
    }
}
