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
    public $dateNum;
    public $week;
    public static $cycleText = [1 => '1周', 2 => '2周', 3 => '1个月',4=>'1天',5=>'2天',6=>'3天',7=>'4天',8=>'5天'];
    public static $cycleNum = [1 => 7, 2 => 14, 3 => 30,4=>1,5=>2,6=>3,7=>4,8=>5];
    public static $typeText = [1 => '体检预约', 2 => '疫苗预约', 8 => '入托体检', 4 => '成人疫苗', 5 => '建册预约', 6 => '领取叶酸预约', 3 => '微量元素',7=>'两癌筛查',9=>'核酸检测',10=>'盆底功能筛查及治疗',11=>'冬病夏治三伏贴',12 => '儿童推拿门诊'];
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
    public static $holiday=[
        '2022-01-01',
        '2022-01-02',
        '2022-01-03',
        '2022-01-31',
        '2022-02-01',
        '2022-02-02',
        '2022-02-03',
        '2022-02-04',
        '2022-01-05',
        '2022-01-06',
        '2022-04-03',
        '2022-04-04',
        '2022-04-05',
        '2022-04-30',
        '2022-05-01',
        '2022-05-02',
        '2022-05-03',
        '2022-05-04',
        '2022-06-03',
        '2022-06-04',
        '2022-06-05',
        '2022-09-10',
        '2022-09-11',
        '2022-09-12',
        '2022-10-01',
        '2022-10-02',
        '2022-10-03',
        '2022-10-04',
        '2022-10-05',
        '2022-10-06',
        '2022-10-07',
    ];

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
        $holiday = HospitalAppoint::$holiday;
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

    /**
     * 获取日期时间段预约数
     * @param $day
     */
    public function getDateNum($day){
        $type=$this->type;
        $doctorid=$this->doctorid;
        $week = date('w', strtotime($day));
        if($this->dateNum[$day]){
            return $this->dateNum;
        }
        //判断预约时间段类型
        $firstAppoint = Appoint::find()
            ->andWhere(['type' => $type])
            ->andWhere(['doctorid' => $doctorid])
            ->andWhere(['appoint_date' => strtotime($day)])
            ->andWhere(['!=', 'state', 3])
            ->andWhere(['mode' => 0])
            ->orderBy('createtime desc')
            ->one();
        $wquery = HospitalAppointWeek::find()->andWhere(['week' => $week])->andWhere(['haid' => $this->id]);

        if($firstAppoint) {
            if ($firstAppoint->appoint_time < 7) {
                $wquery->andWhere(['<', 'time_type', 7]);
            }
            if ($firstAppoint->appoint_time > 6) {
                $wquery->andWhere(['>', 'time_type', 6]);
            }
        }else{
            if($this->interval==1){
                $wquery->andWhere(['<', 'time_type', 7]);

            }
            if($this->interval==2){
                $wquery->andWhere(['>', 'time_type', 6]);
            }
        }

        $weeks=$wquery->all();
        if ($weeks) {
            $appoints = Appoint::find()
                ->select('count(*)')
                ->andWhere(['type' => $type])
                ->andWhere(['doctorid' => $doctorid])
                ->andWhere(['appoint_date' => strtotime($day)])
                ->andWhere(['!=', 'state', 3])
                ->andWhere(['mode' => 0])
                ->indexBy('appoint_time')
                ->groupBy('appoint_time')
                ->column();
            foreach ($weeks as $k => $v) {

                if ($appoints) {
                    $num = $v->num - $appoints[$v->time_type];
                    $rs[$v->time_type] = $num > 0 ? $num : 0;
                }else{
                    $rs[$v->time_type] = $v->num;
                }
            }

            if ($doctorid != 176156) {
                unset($rs[19]);
                unset($rs[20]);
            }
        }
        $this->dateNum[$day]=$rs;
        return $rs;
    }

    /**
     * 设置疫苗预约数量
     *
     */
    public function getVaccineNum($vid,$day){

        $type=$this->type;
        $doctorid=$this->doctorid;
        $week = date('w', strtotime($day));
        $vWeek=[];
        $vTypes=[];
        //获取疫苗预约时间（上午/下午）
        if($vid) {
            $vaccine = Vaccine::findOne($vid);
            $query = HospitalAppointVaccine::find()
                ->where(['haid' => $this->id,'week' => $week]);

            if ($vaccine->type == 0) {
                $query->andWhere(['or', ['vaccine' => $vid], ['vaccine' => 0]]);
            } else {
                $query->andWhere(['or', ['vaccine' => $vid], ['vaccine' => -1]]);
            }
            $vWeek = $query->select('type')->column();

            //如果该日期只设置了上午则代表设置的上午疫苗全天可约
            $vTypes = HospitalAppointVaccine::find()->select('type')
                ->where(['haid' => $this->id,'week' => $week])->column();

        //判断所选疫苗是否按照时间段分配
            $hospitalAppointVaccineTimeNum=HospitalAppointVaccineTimeNum::find()
                ->where(['vaccine'=>$vid,'week'=>$week,'type'=>$type,'doctorid'=>$doctorid])
                ->select('num')
                ->indexBy('appoint_time')
                ->column();
            if($hospitalAppointVaccineTimeNum) {
                //获取当前时间段该疫苗已预约数量
                $appointVaccineNum = Appoint::find()
                    ->select('count(*)')
                    ->andWhere(['type' => $type])
                    ->andWhere(['doctorid' => $doctorid])
                    ->andWhere(['appoint_date' => strtotime($day)])
                    ->andWhere(['vaccine' => $vid])
                    ->andWhere(['!=', 'state', 3])
                    ->andWhere(['mode' => 0])
                    ->indexBy('appoint_time')
                    ->groupBy('appoint_time')
                    ->column();
            }
        }

        foreach ($this->getDateNum($day) as $k => $v) {
            if(in_array($k,[1,2,3,7,8,9,10,11,12,19,20]) && !in_array(1,$vWeek) && $vWeek){
                $v=0;
            }elseif(in_array($k,[4,5,6,13,14,15,16,17,18]) && !in_array(2,$vWeek) && in_array(2,$vTypes)&& $vWeek){
                $v=0;
            }
            $num=$v;

            if($hospitalAppointVaccineTimeNum){
                if($hospitalAppointVaccineTimeNum[$k]) {
                    if($v>=($hospitalAppointVaccineTimeNum[$k]-$appointVaccineNum[$k])) {
                        $num = ($hospitalAppointVaccineTimeNum[$k]-$appointVaccineNum[$k]);
                    }
                }else{
                    $num = 0;
                }
            }
            $rows['time'] = Appoint::$timeText[$k];
            $rows['appoint_time'] = $k;
            $rows['num'] = $num;
            $times[] = $rows;
        }
        return $times;
    }

}
