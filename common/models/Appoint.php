<?php

namespace common\models;

use common\components\HttpRequest;
use common\models\queuing\Queue;
use Yii;

/**
 * This is the model class for table "appoint".
 *
 * @property int $id
 * @property int $userid
 * @property int $doctorid
 * @property int $createtime
 * @property int $appoint_time
 * @property int $appoint_date
 * @property int $type
 * @property int $childid
 * @property int $phone
 * @property int $state
 * @property int $remark
 * @property int $cancel_type
 * @property int $push_state
 * @property int $mode
 * @property int $vaccine
 * @property string $name
 */

class Appoint extends \yii\db\ActiveRecord
{
    public static $timeText = [
        '1' => '08:00-09:00',
        '2' => '09:00-10:00',
        '3' => '10:00-11:00',
        '4' => '13:00-14:00',
        '5' => '14:00-15:00',
        '6' => '15:00-15:30',
        '7' =>'08:00-08:30',
        '8' =>'08:30-09:00',
        '9' =>'09:00-09:30',
        '10'=>'09:30-10:00',
        '11'=>'10:00-10:30',
        '12'=>'10:30-11:00',
        '19'=>'11:00-11:30',
        '20'=>'11:30-12:00',
        '13'=>'13:00-13:30',
        '14'=>'13:30-14:00',
        '15'=>'14:00-14:30',
        '16'=>'14:30-15:00',
        '17'=>'15:00-15:30',
        '18'=>'15:30-16:00'
    ];
    public static $timeText4 = [
        1=>[
            '1' => '08:00-09:00',
            '2' => '09:00-10:00',
            '3' => '10:00-11:00',
            '4' => '13:00-14:00',
            '5' => '14:00-15:00',
            '6' => '15:00-15:30',
        ],
        2=>[
            '7' =>'08:00-08:30',
            '8' =>'08:30-09:00',
            '9' =>'09:00-09:30',
            '10'=>'09:30-10:00',
            '11'=>'10:00-10:30',
            '12'=>'10:30-11:00',
            '19'=>'11:00-11:30',
            '20'=>'11:30-12:00',
            '13'=>'13:00-13:30',
            '14'=>'13:30-14:00',
            '15'=>'14:00-14:30',
            '16'=>'14:30-15:00',
            '17'=>'15:00-15:30',
            '18'=>'15:30-16:00'
        ],
        
    ];
    public static $timeText3 = [
        '1' => '08:00-09:30',
        '2' => '09:00-10:30',
        '3' => '10:00-11:30',
        '4' => '13:00-14:30',
        '5' => '14:00-15:30',
        '6' => '15:00-15:30',
        '7' =>'08:00-09:00',
        '8' =>'08:30-09:30',
        '9' =>'09:00-10:00',
        '10'=>'09:30-10:30',
        '11'=>'10:00-11:00',
        '12'=>'10:30-11:30',
        '19'=>'11:00-11:30',
        '20'=>'11:30-12:00',
        '13'=>'13:00-14:00',
        '14'=>'13:30-14:30',
        '15'=>'14:00-15:00',
        '16'=>'14:30-15:30',
        '17'=>'15:00-16:00',
        '18'=>'15:30-16:00'
    ];
    public static $timeText1 = [
        '1' => '08:00-09:00',
        '2' => '09:00-10:00',
        '3' => '10:00-11:00',
        '4' => '13:00-14:00',
        '5' => '14:00-15:00',
        '6' => '15:00-16:00',
        '7' =>'08:00-08:30',
        '8' =>'08:30-09:00',
        '9' =>'09:00-09:30',
        '10'=>'09:30-10:00',
        '11'=>'10:00-10:30',
        '12'=>'10:30-11:00',
        '19'=>'11:00-11:30',
        '20'=>'11:30-12:00',
        '13'=>'13:00-13:30',
        '14'=>'13:30-14:00',
        '15'=>'14:00-14:30',
        '16'=>'14:30-15:00',
        '17'=>'15:00-15:30',
        '18'=>'15:30-16:00'
    ];
    public static $timeText2 = [
        '1' => '08:00',
        '2' => '09:00',
        '3' => '10:00',
        '4' => '13:00',
        '5' => '14:00',
        '6' => '15:00',
        '7' =>'08:00',
        '8' =>'08:30',
        '9' =>'09:00',
        '10'=>'09:30',
        '11'=>'10:00',
        '12'=>'10:30',
        '19'=>'11:00',
        '20'=>'11:30',
        '13'=>'13:00',
        '14'=>'13:30',
        '15'=>'14:00',
        '16'=>'14:30',
        '17'=>'15:00',
        '18'=>'15:30'
    ];

    public static $timeTextRow=[
        1=>[
            1=>[
                '1' => '08:00-09:00',
                '2' => '09:00-10:00',
                '3' => '10:00-11:00',
            ],
            2=>[
                '4' => '13:00-14:00',
                '5' => '14:00-15:00',
                '6' => '15:00-16:00',
            ],
        ],
        2=>[
            1=>[
                '7' =>'08:00-08:30',
                '8' =>'08:30-09:00',
                '9' =>'09:00-09:30',
                '10'=>'09:30-10:00',
                '11'=>'10:00-10:30',
                '12'=>'10:30-11:00',
                '19'=>'11:00-11:30',
                '20'=>'11:30-12:00',
            ],
            2=>[
                '13'=>'13:00-13:30',
                '14'=>'13:30-14:00',
                '15'=>'14:00-14:30',
                '16'=>'14:30-15:00',
                '17'=>'15:00-15:30',
                '18'=>'15:30-16:00'
            ],
        ],
    ];
    public static function getTimeType($interval,$time){

        if($interval==1){
            if($time<"08:00") {
                return 1;
            }elseif ($time>"11:00" && $time<"13:00"){
                return 4;
            }
        }
        if($interval==2){
            if($time<"08:00") {
                return 7;
            }elseif ($time>"12:00" && $time<"13:00"){
                return 13;
            }
        }
        foreach(self::$timeText1 as $k=>$v){
            if($interval==1 && $k<7){
                $rs=explode('-',$v);
                if($rs[0]<=$time && $rs[1]>=$time){
                    return $k;
                }
            }
            if($interval==2 && $k>6){
                $rs=explode('-',$v);
                if($rs[0]<=$time && $rs[1]>=$time){
                    return $k;
                }
            }
        }
        return 0;
    }
    public static function getTimeTypes($interval,$time){

        foreach(self::$timeText1 as $k=>$v){
            if($interval==1 && $k<7){
                $rs=explode('-',$v);
                if($rs[0]<=$time && $rs[1]>=$time){
                    return $k;
                }
            }
            if($interval==2 && $k>6){
                $rs=explode('-',$v);
                if($rs[0]<=$time && $rs[1]>=$time){
                    return $k;
                }
            }
        }
        return 0;
    }

    public static function getTimeTypeTmp($doctorid,$type){
        //排队
        $t=date('H:s');
        $key=$t<'12:00'?1:2;
        if($t>'16:00' || $t<'07:00'){
            return false;
        }
        $hospitalAppoint = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => $type]);
        $array=array_reverse(Appoint::$timeTextRow[$hospitalAppoint->interval][$key],true);

        $i=0;
        while (($i==0 && $mode = reset($array)) || (($i>0 &&$mode = next($array)) !== false)) {
            $i++;
            $timeType = array_search($mode, Appoint::$timeText1);
            $appoint_time=$timeType;
//            $queue = new Queue($doctorid,$type, $timeType);
//            $list = $queue->lrange();
            $times=explode('-',$mode);
            if($t>$times[0] && $t<$times[1]){
                break;
            }
        }
        return $appoint_time;
    }


    public static $stateText = [
        0 => '待审核',
        1 => '进行中',
        2 => '已完成',
        3 => '已取消',
        4 => '已过期',
        5 => '待确认（加号）',
        6 => '待确认（排队）',
        7 => '预约失败',

    ];
    public static $typeText = [
        1 => '儿童体检',
        2 => '儿童疫苗',
        8 => '入托体检',
        4 => '成人疫苗',
        5 => '建册预约',
        6 => '领取叶酸预约',
        3 => '微量元素',
        7 => '两癌筛查',
        9 => '核酸检测',
        10 => '盆底功能筛查及治疗',
        11 => '冬病夏治三伏贴',
        12 => '儿童推拿门诊',
        13 => '专病预约',
    ];

    public static $typeText1 = [
        1 => '体检',
        2 => '接种',
    ];
    public static $typeInfoText = [
        1 => '身高、体重、头围、面色等检查项目',
        2 => '身乙肝疫苗、卡介苗、百日破疫苗',
        3 => '微量元素检查，锌、铁、钙',
    ];
    public static $cancel_typeText=[
        1 => '天气原因',
        2 => '时间原因',
        3 => '身体不适',
        4 => '其他',
        5 => '预约失败'
    ];
    public static $hospital_cancel=[
        6=>'不属于管辖街道',
        7=>'资料不完整/不合格,请上传资料重新预约',
        8=>'请持医保卡等现场登记预约',
        9=>'重复预约',
    ];
    public  static $push_stateText=[
        1=>'发送中',
        2=>'微信模板发送成功',
        3=>'短信发送成功',
        4=>'微信模板发送失败',
        5=>'短信发送失败',
    ];
    public static $modeText=[
        0=>'用户预约',
        1=>'医生预约',
        2=>'系统导入',
        3=>'西城预约',

    ];

    public $date;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appoint';
    }


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['z'] = ['vaccine','appoint_time','appoint_date','type','userid','doctorid',];
        return $scenarios;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['vaccine'],'required','message'=>'专病不能为空','on'=>['z']],

            [['appoint_date','type'], 'required'],
            ['appoint_time','required','message'=>'请选择预约时间'],
            ['appoint_date','required','message'=>'请选择预约日期'],

            [['street','orderid','vaccine','push_state','mode','cancel_type','loginid', 'userid', 'doctorid', 'createtime', 'appoint_time', 'appoint_date', 'type', 'childid', 'phone', 'state'], 'integer'],
            [['remark'], 'string', 'max' => 100],
            [['date','name','source'], 'string'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => '父母',
            'doctorid' => '预约社区医院',
            'createtime' => '创建时间',
            'appoint_time' => '预约时间',
            'appoint_date' => '预约日期',
            'date' => '预约日期',
            'type' => '预约项目',
            'childid' => '儿童',
            'phone' => '手机号',
            'state' => '预约状态',
            'loginid' => '登录信息',
            'remark'=>'备注',
            'cancel_type'=>'取消原因',
            'push_state'=>'推送状态',
            'mode'=>'来源',
            //'image'=>'凭证',
            'vaccine'=>'疫苗'
        ];
    }

    public function name(){
        if($this->type==4 || $this->type==7 || $this->type==9 || $this->type==11 || $this->type==12){
            if($this->childid){
                return \common\models\AppointAdult::findOne(['id' => $this->childid])->name;
            }else {
                return \common\models\AppointAdult::findOne(['userid' => $this->userid])->name;
            }

        }elseif($this->type==5 || $this->type==6){
            return \common\models\Pregnancy::findOne(['id' => $this->childid])->field1;
        }elseif($this->type==10){
            return $this->name;
        }else{
            return \common\models\ChildInfo::findOne(['id' => $this->childid])->name;
        }
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->createtime = time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

}
