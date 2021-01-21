<?php

namespace common\models;

use console\models\ChildInfoInput;
use Yii;

/**
 * This is the model class for table "{{%child_info}}".
 * @property int $id 主键
 * @property int $userid 关联家长ID
 * @property string $name 姓名
 * @property int $birthday
 * @property int $createtime
 * @property int $gender 1男2女
 * @property int $idcard 儿童身份证
 * @property int $source 导入来源医院ID
 * @property int $doctorid 所属医院ID
 * @property string $field54 新建地点
 * @property string $field53 其他遗传代谢病
 * @property string $field52 新生儿疾病筛查耳
 * @property string $field51 新生儿疾病筛查甲
 * @property string $field50 所在居委会
 * @property string $field49 新生儿听力筛查
 * @property string $field48 新生儿窒息
 * @property string $field47 分娩方式
 * @property string $field46 先天畸形
 * @property string $field45 胎数
 * @property string $field44 产伤
 * @property string $field43 颅内出血
 * @property string $field42 出生孕周
 * @property string $field41 母孕期情况
 * @property string $field40 助产机构名称
 * @property string $field39 出院日期
 * @property string $field38 母亲第几产
 * @property string $field37 母亲第几胎
 * @property string $field27 儿童身份证号
 * @property string $field26 儿童疾病记录
 * @property string $field25 新生儿访视状态
 * @property string $field24 管理机构
 * @property string $field23 建册机构
 * @property string $field22 过敏史
 * @property string $field21 高危儿童状态
 * @property string $field20 残疾儿状态
 * @property string $field19 先天性髋脱位状态
 * @property string $field18 先天性心脏病状态
 * @property string $field17 肥胖状态
 * @property string $field16 营养不良状态
 * @property string $field15 佝偻病状态
 * @property string $field14 贫血状态
 * @property string $field13 体弱儿状态
 * @property string $field7 条形码
 * @property string $field6 出生医学证明号
 * @property string $field0 建册时间
 */
class ChildInfo extends \yii\db\ActiveRecord
{
    public static $genderText=[0=>'未设置',1=>"男",2=>"女"];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {

        return '{{%child_info}}';
    }

    public static $field=[
        'name' => '儿童姓名',
        'birthday' => '出生日期',
        'gender' => '性别',
        'field54' => '新建地点',
        'field53' => '其他遗传代谢病',
        'field52' => '新生儿疾病筛查（耳聋基因筛查）',
        'field51' => '新生儿疾病筛查（甲低和苯丙酮尿症）',
        'field50' => '所在居委会',
        'field49' => '新生儿听力筛查',
        'field48' => '新生儿窒息',
        'field47' => '分娩方式',
        'field46' => '先天畸形',
//        'field45' => '胎数',
//        'field44' => '产伤',
        'field43' => '颅内出血',
        'field42' => '出生孕周',
        'field41' => '母孕期情况',
        'field40' => '助产机构名称',
        'field39' => '出院日期',
        'field38' => '母亲第几产',
        'field37' => '母亲第几胎',
        'field27' => '儿童身份证号',
        'field26' => '儿童疾病记录',
        'field25' => '新生儿访视状态',
        'field24' => '管理机构',
        'field23' => '建册机构',
        'field22' => '过敏史',
        'field21' => '高危儿童状态',
        'field20' => '残疾儿状态',
        'field19' => '先天性髋脱位状态',
        'field18' => '先天性心脏病状态',
        'field17' => '肥胖状态',
        'field16' => '营养不良状态',
        'field15' => '佝偻病状态',
        'field14' => '贫血状态',
        'field13' => '体弱儿状态',
        'field7' => '条形码',
        'field6' => '出生医学证明号',
        'field0' => '建册时间',
        'mother' => '母亲姓名',
        'mother_phone' => '母亲联系电话',
        'mother_id' => '母亲身份证号',
        'father' => '父亲姓名',
        'father_phone' => '父亲联系电话',
        'father_birthday' => '父亲生日',
        'state' => '居住状态',
        'address' => '户籍所在省（北京市）',
        'field34' => '父亲文化程度',
        'field33' => '父亲职业',
        'field30' => '母亲文化程度',
        'field29' => '母亲职业',
        'field28' => '母亲出生日期',
        'field12' => '联系人电话',
        'field11' => '联系人姓名',
        'field44' => '户籍所在省（北京市）',
        'field45' => '户籍地址区',
        'field1' => '户口',
        'fieldu46'=>'现住址地址',
        'fieldu47'=>'儿童所在段区',
        'fieldp47'=>'现住址详细',

    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'birthday', 'createtime', 'gender', 'source', 'admin', 'doctorid'], 'integer'],
            [['fieldu47','idcard','name', 'field54', 'field53', 'field52', 'field51', 'field50', 'field49', 'field48', 'field47', 'field46', 'field45', 'field44', 'field43', 'field42', 'field41', 'field40', 'field39', 'field38', 'field37', 'field27', 'field26', 'field25', 'field24', 'field23', 'field22', 'field21', 'field20', 'field19', 'field18', 'field17', 'field16', 'field15', 'field14', 'field13', 'field7', 'field6', 'field0'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'userid' => '关联家长ID',
            'name' => '姓名',
            'birthday' => '生日',
            'createtime' => '数据创建日期',
            'gender' => '1男2女',
            'admin'=>'管理机构',
            'doctorid'=>'所属医院ID',
            'source' => '导入来源医院ID',
            'field54' => '新建地点',
            'field53' => '其他遗传代谢病',
            'field52' => '新生儿疾病筛查耳',
            'field51' => '新生儿疾病筛查甲',
            'field50' => '所在居委会',
            'field49' => '新生儿听力筛查',
            'field48' => '新生儿窒息',
            'field47' => '分娩方式',
            'field46' => '先天畸形',
            'field45' => '胎数',
            'field44' => '产伤',
            'field43' => '颅内出血',
            'field42' => '出生孕周',
            'field41' => '母孕期情况',
            'field40' => '助产机构名称',
            'field39' => '出院日期',
            'field38' => '母亲第几产',
            'field37' => '母亲第几胎',
            'field27' => '儿童身份证号',
            'field26' => '儿童疾病记录',
            'field25' => '新生儿访视状态',
            'field24' => '管理机构',
            'field23' => '建册机构',
            'field22' => '过敏史',
            'field21' => '高危儿童状态',
            'field20' => '残疾儿状态',
            'field19' => '先天性髋脱位状态',
            'field18' => '先天性心脏病状态',
            'field17' => '肥胖状态',
            'field16' => '营养不良状态',
            'field15' => '佝偻病状态',
            'field14' => '贫血状态',
            'field13' => '体弱儿状态',
            'field7' => '条形码',
            'field6' => '出生医学证明号',
            'field0' => '建册时间',
            'fieldu47'=>'儿童所在段区',
            'idcard'=>'儿童身份证',
        ];
    }

    public static function GetInfoByParentid($parentid,$where=null)
    {
        $model=ChildInfo::find()->where(['userid'=>$parentid]);
        if (!empty($where)) {
            $model->andwhere($where);
        }
        return $model->all();
    }
    public static function getChildTypeDay($childType){
        return Article::$childMonth[$childType]?strtotime(date('Y-m-d',strtotime('-'.Article::$childMonth[$childType]." month -1 day"))):0;
    }

    /**
     * @param int $type 区分判断儿童月龄方式
     * @return int|string
     */
    public function getType($type=0)
    {

        $end = time();
        $start = new \DateTime("@$this->birthday");
        $end   = new \DateTime("@$end");
        $diff  = $start->diff($end);
        $month=$diff->format('%y') * 12 + $diff->format('%m');


        if($type==1) {
            $childMonth = [
                1 => ['a' => 1, 'b' => 2],
                2 => ['a' => 3, 'b' => 4],
                3 => ['a' => 4, 'b' => 7],
                4 => ['a' => 7, 'b' => 9],
                5 => ['a' => 11, 'b' => 13],
                6 => ['a' => 17, 'b' => 19],
                7 => ['a' => 23, 'b' => 25],
                8 => ['a' => 29, 'b' => 32],
                9 => ['a' => 35, 'b' => 42],
                10 => ['a' => 46, 'b' => 54],
                11 => ['a' => 58, 'b' => 66],
                12 => ['a' => 70, 'b' => 72],
            ];
            foreach($childMonth as $k=>$v){
                if($v['a']<=$month and $v['b']>$month){
                    return $k;
                }
            }
        }else{
            $childMonth=Article::$childMonth;
            foreach($childMonth as $k=>$v){
                if($v<=$month){
                    return $k;
                }
            }
        }


        return 0;
    }


    public static function getChildType($childType)
    {
        switch ($childType)
        {
            case 1:
                $n=3;$l=2;
                break;
            case 2:
                $n=6;$l=3;
                break;
            case 3:
                $n=8;$l=2;
                break;
            case 4:
                $n=12;$l=4;
                break;
            case 5:
                $n=18;$l=6;
                break;
            case 6:
                $n=24;$l=6;
                break;
            case 7:
                $n=30;$l=6;
                break;
            case 8:
                $n=36;$l=6;
                break;
            case 9:
                $n=48;$l=12;
                break;
            case 10:
                $n=60;$l=12;
                break;
            case 11:
                $n=72;$l=12;
                break;
            case 12:
                $n=84;$l=12;
                break;
            default :
                $n=0;$l=0;
                break;

        }
        $month=self::month($n,$l);
        return $month;

    }

    /**
     * @param $n
     * @param $l
     * @return array
     */
    public static function month($n,$l)
    {
        $timestamp=time();
        $firstday=strtotime(date('Y-m-02',strtotime(date('Y-m')." -$n month")));
        $day=date('Y-m',$firstday);
        $lastday=strtotime("$day +$l month");
        return ['firstday'=>$firstday,'lastday'=>$lastday];
    }


    //通过id获取儿童信息  刘方露
    public static function GetChildInfo($childid)
    {
        return ChildInfo::find()->where(['id'=>$childid])->one();
    }

    //通过parentid获取儿童数目  刘方露
    public static function GetSumChild($parentid)
    {
        return ChildInfo::find()->where(['userid'=>$parentid])->count('id');
    }

    //通过宝宝id获取父母id
    public static function GetParentId($childid)
    {
       return ChildInfo::find()->select('userid')->where(['id'=>$childid])->one();
    }

    public function getParent()
    {
        return $this->hasOne(UserParent::className(),["userid"=>"userid"]);
    }

    public function getDocpar()
    {
        return$this->hasOne(DoctorParent::className(),['userid'=>'parentid']);
    }
    public function getUser()
    {
        return $this->hasOne(User::className(),['id'=>'userid']);
    }
    public function getSign()
    {
        return $this->hasOne(DoctorParent::className(),['parentid'=>'userid']);
    }

    public function getDoctor()
    {
        return $this->hasMany(UserDoctor::className(), ['userid' => 'doctorid'])
            ->viaTable('doctor_parent', ['parentid' => 'userid']);
    }

    public function getLogin()
    {
        return $this->hasOne(UserLogin::className(),['userid'=>'userid']);
    }
    public function beforeSave($insert)
    {
        if($insert)
        {
            $this->createtime=time();
        }
        $doctorParent=DoctorParent::findOne(['parentid'=>$this->userid,'level'=>1]);
        if($doctorParent && $doctorParent->doctorid){
            $doctor = UserDoctor::findOne(['userid'=>$doctorParent->doctorid]);
            $this->doctorid=$doctor->hospitalid?$doctor->hospitalid:$this->source;
        }else{
            $this->doctorid=$this->source?$this->source:0;
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public static function inputData($row,$hospitalid){
        $ChildInfoInput=new ChildInfoInput();
        return $ChildInfoInput->inputData($row,$hospitalid);

    }
}
