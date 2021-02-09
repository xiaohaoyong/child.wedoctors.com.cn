<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hospital_appoint".
 *
 * @property int $id
 * @property int $doctorid
 * @property int $cycle
 * @property int $delay
 * @property int type
 * @property int weeks
 * @property int updateInterval
 * @property int phone
 * @property int info
 * @property int interval
 * @property string non_date
 * @property string sure_date
 * @property int release_time
 * @property int  is_month
 */
class HospitalAppoint extends \yii\db\ActiveRecord
{
    public $week;
    public static $cycleText = [1 => '1周', 2 => '2周', 3 => '1个月',4=>'1天',5=>'2天',6=>'3天',7=>'4天',8=>'5天'];
    public static $cycleNum = [1 => 7, 2 => 14, 3 => 30,4=>1,5=>2,6=>3,7=>4,8=>5];
    public static $typeText = [1 => '体检预约', 2 => '疫苗预约', 8 => '入托体检', 4 => '成人疫苗', 5 => '建册预约', 6 => '领取叶酸预约', 3 => '微量元素',7=>'两癌筛查',9=>'核酸检测'];
    public static $intervalText = [1 => '一小时', 2 => '半小时'];
    public static $rtText=[
        0=>'00:00',
        5=>'05:00',
        6=>'06:00',
        7=>'07:00',
        11=>'11:00',
        12=>'12:00',
        13=>'13:00',
        14=>'14:00',
        15=>'15:00',
        16=>'16:00',
        17=>'17:00',
        18=>'18:00',
        19=>'19:00',
        20=>'20:00',
        21=>'21:00',
        22=>'22:00',
        23=>'23:00',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hospital_appoint';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cycle', 'delay', 'week','interval','phone'], 'required'],
            [['is_month','doctorid', 'cycle', 'delay', 'weeks', 'interval', 'updateInterval', 'phone','release_time'], 'integer'],
            [['info'], 'string', 'max' => 350],
            [['sure_date','non_date'],'dateNumValidation']
        ];
    }

    public function dateNumValidation($attribute,$params){

        if(isset($this->$attribute)){
            $array=explode(',',$this->$attribute);
            if(count($array)>20){
                $this->addError($attribute, "不能超过20个日期，请清除过期日期");
            }
        }

    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'doctorid' => 'Doctorid',
            'cycle' => 'Cycle',
            'delay' => 'Delay',
            'type' => 'Type',
            'weeks' => 'weeks',
            'interval' => '预约时间段',
            'updateInterval' => '间隔时间段上线时间',
            'is_month'=>'体检月龄限制',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->week) {
            $this->weeks = implode('', $this->week);
        }
        if (!$insert) {
            $this->updateInterval = time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * 判断日期是否可约
     * @param $date
     * @param int $vid
     * @return bool
     */
    public function is_appoint($date,$weekr=[])
    {

        $week = date('w', $date);
        $weeks =str_split((string)$this->weeks);
        $weeks = $weekr?array_intersect($weekr, $weeks):$weeks;
        $holiday = [
            '2021-01-01',
            '2021-01-02',
            '2021-01-03',
            '2021-02-11',
            '2021-02-12',
            '2021-02-13',
            '2021-02-14',
            '2021-02-15',
            '2021-02-16',
            '2021-02-17',
            '2021-04-03',
            '2021-04-04',
            '2021-04-05',
            '2021-05-01',
            '2021-05-02',
            '2021-05-03',
            '2021-05-04',
            '2021-05-05',
            '2021-06-12',
            '2021-06-13',
            '2021-06-14',
            '2021-09-19',
            '2021-09-20',
            '2021-09-21',
            '2021-10-01',
            '2021-10-02',
            '2021-10-03',
            '2021-10-04',
            '2021-10-05',
            '2021-10-06',
            '2021-10-07',
        ];
        if($this->non_date) {
            $non_date =explode(',',$this->non_date);
            $holiday=array_merge($holiday,$non_date);
        }
        $sure_date=[];
        if($this->sure_date) {
            $sure_date =explode(',',$this->sure_date);
        }

        //$sure_date=[1593273600];
        $cycle=self::$cycleNum[$this->cycle];
        $sday = strtotime(date('Y-m-d', strtotime('+' . ($this->delay+1) . " day")));
        $eday = strtotime('+'.($cycle-1).' day',$sday);


        if($sure_date && $weekr) {
            if (in_array(date('Y-m-d', $date), $sure_date) && in_array($week, $weekr)) {
                return 1;
            }
        }elseif($sure_date && in_array(date('Y-m-d', $date), $sure_date) ){
            if(($date>=$sday && $date<$eday) || ($date==$eday && date('Gi')>$this->release_time."00")){
                return 1;
            }else{
                return 2;
            }
        }
        if (in_array($week, $weeks) && !in_array(date('Y-m-d', $date), $holiday)) {
            if(($date>=$sday && $date<$eday) || ($date==$eday && date('Gi')>$this->release_time."00")){
                return 1;
            }else{
                return 2;
            }
        }
        return 0;

    }

}
