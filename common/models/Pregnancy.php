<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pregnancy".
 *
 * @property int $id
 * @property int $familyid
 * @property string $field0 条形码
 * @property string $field1 产妇姓名
 * @property string $field2 出生日期
 * @property int $field3 证件类型
 * @property string $field4 证件号码
 * @property string $field5 建册日期
 * @property string $field6 联系电话
 * @property int $field7 户口所在地-省
 * @property int $field8 居住地-省
 * @property int $field9 居住地-市
 * @property string $field10 居住地-详细
 * @property string $field11 末次月经
 * @property string $field12 初诊日期
 * @property int $field13 初检孕周
 * @property int $field14 B超核实
 * @property string $field15 核实后预产期
 * @property string $field16 核实后末次月经
 * @property int $field17 建册孕周
 * @property int $field18 孕次
 * @property int $field19 产次
 * @property int $field20 自然流产次数
 * @property int $field21 药物及人工流产次数
 * @property int $field22 叶酸使用情况
 * @property int $field23 叶酸服用是否规律
 * @property int $field24 家族史
 * @property string $field25 家族史-详细
 * @property string $field26 既往史
 * @property string $field27 既往史-详细
 * @property int $field28 死胎数
 * @property int $field29 死产数
 * @property int $field30 新生儿死亡数
 * @property string $field31 上次分娩日期
 * @property int $field32 上次分娩方式
 * @property string $field33 妊娠期合并症、并发症
 * @property string $field34 围产高危
 * @property int $field35 高危级别
 * @property string $field36 丈夫姓名
 * @property string $field37 丈夫证件号
 * @property string $field38 丈夫联系电话
 * @property int $field39 丈夫户口地址-省
 * @property int $field40 丈夫居住地-省
 * @property int $field41 丈夫居住地-市
 * @property string $field42 丈夫居住地-详细
 * @property int $field43 丈夫年龄
 * @property string $field44 产检地点
 * @property int $field45 本地农业户籍
 * @property int $field46 农村孕产妇住院分娩补助
 * @property int $field47 产前检查补助
 * @property int $field48 高危
 * @property int $field49 分娩
 * @property int $field50 产后访视
 * @property int $field51 访视次数
 * @property int $field52 首检标识
 * @property int $field53 是否出院
 * @property int $field54 产前诊断
 * @property int $field55 超声筛查
 * @property int $field56 血生化筛查
 * @property int $field57 妊娠状态
 * @property string $field58 出院日期
 * @property string $field59 当前高危因素
 * @property string $field60 预约时间
 * @property string $field61 追访日期
 * @property int $field62 追访次数
 * @property string $field63 录入日期
 * @property int $field64 是否发放艾滋病病毒(HIV)抗体免费检测卡
 * @property int $field65 符合单独二孩政策的第二胎
 * @property string $field66 当前高危因素（20180312之前版）
 * @property int $field67 高危级别（20180312之前版）
 * @property int $field68 本次妊娠为
 * @property int $field70 身高
 * @property string $field71 孕前体重
 * @property string $field72 分娩日期
 * @property string $field73 BMI指数
 * @property int $field74 BMI分类
 * @property string $field75 收缩压
 * @property int $field76 分娩机构
 * @property int $field77 舒张压
 * @property int $field78 发放国家母子健康手册
 * @property string $field79 高危因素（2018新版）
 * @property int $field80 使用国家母子健康手册
 * @property int $field81 高危级别（2018新版）
 * @property string $field82 产检机构
 * @property string $field83 产检日期
 * @property int $field84 产时产后并发症（蓝球）
 * @property int $field85 传染病（紫球）
 * @property string $field86 地段初筛筛查结果详细（2018版）
 * @property int $field87 地段初筛筛查结果
 * @property int $field88 档案来源
 * @property int $field89 围产保健登记卡生成状态
 * @property int $field90 户籍地
 * @property int $source
 * @property int $isupdate
 * @property int $createtime 创建日期
 * @property int $doctorid
 */
class Pregnancy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pregnancy';
    }

    public function getWeeks(){

        return ceil((time()-($this->field16?$this->field16:$this->field11))/60/60/24/7-1);
    }

    public static $bmi=[
        1=>'超重',
        2=>'肥胖',
        3=>'正常',
        4=>'瘦',
    ];
    public static $field81=[
        0=>'无',
        1=>'低风险',
        2=>'高风险',
        3=>'较高风险',
        4=>'一般风险',
    ];
    public static $field90=[
        0=>'无',
        1=>'孕妇为本市户籍',
        2=>'孕妇外地配偶本市(外嫁京)',
        3=>'孕妇及其配偶均为外地户籍',
    ];
    public static $field49=[
        0=>'否',
        1=>'是',
    ];


    public static $field=[
        0=>'产妇条码',
        1=>'产妇姓名',
        2=>'出生日期',
        //3=>'证件类型',
        4=>'证件号码',
        5=>'建册日期',
        6=>'产妇电话',
        7=>'户口所在地-省',
        8=>'居住地-省',
        9=>'居住地-市',
        10=>'居住地-详细',
        11=>'末次月经',
//        12=>'初诊日期',
        13=>'初检孕周',
//        14=>'B超核实',
        15=>'核实后预产期',
        16=>'核实后末次月经',
        17=>'建册孕周',
//        18=>'孕次',
//        19=>'产次',
//        20=>'自然流产次数',
//        21=>'药物及人工流产次数',
//        22=>'叶酸使用情况',
//        23=>'叶酸服用是否规律',
//        24=>'家族史',
//        25=>'家族史-详细',
//        26=>'既往史',
//        27=>'既往史-详细',
//        28=>'死胎数',
//        29=>'死产数',
//        30=>'新生儿死亡数',
//        31=>'上次分娩日期',
//        32=>'上次分娩方式',
//        33=>'妊娠期合并症、并发症',
//        34=>'围产高危',
//        35=>'高危级别',
        36=>'丈夫姓名',
       // 37=>'丈夫证件号',
       // 38=>'丈夫联系电话',
        //39=>'丈夫户口地址-省',
//        40=>'丈夫居住地-省',
//        41=>'丈夫居住地-市',
//        42=>'丈夫居住地-详细',
//        43=>'丈夫年龄',
//        44=>'产检地点',
//        45=>'本地农业户籍',
//        46=>'农村孕产妇住院分娩补助',
//        47=>'产前检查补助',
//        48=>'高危',
        49=>'是否分娩',
//        50=>'产后访视',
//        51=>'访视次数',
//        52=>'首检标识',
//        53=>'是否出院',
//        54=>'产前诊断',
//        55=>'超声筛查',
//        56=>'血生化筛查',
//        57=>'妊娠状态',
//        58=>'出院日期',
//        59=>'当前高危因素',
//        60=>'预约时间',
        61=>'追访日期',
        62=>'追访次数',
//        63=>'录入日期',
//        64=>'是否发放艾滋病病毒(HIV)抗体免费检测卡',
//        65=>'符合单独二孩政策的第二胎',
//        66=>'当前高危因素（20180312之前版）',
//        67=>'高危级别（20180312之前版）',
//        68=>'本次妊娠为',
        70=>'身高',
        71=>'孕前体重',
//        72=>'分娩日期',
        73=>'BMI指数',
        74=>'BMI分类',
//        75=>'收缩压',
//        76=>'分娩机构',
//        77=>'舒张压',
//        78=>'发放国家母子健康手册',
        //79=>'高危因素（2018新版）',
//        80=>'使用国家母子健康手册',
        81=>'高危级别（2018新版）',
//        82=>'产检机构',
//        83=>'产检日期',
//        84=>'产时产后并发症（蓝球）',
//        85=>'传染病（紫球）',
//        86=>'地段初筛筛查结果详细（2018版）',
//        87=>'地段初筛筛查结果',
//        88=>'档案来源',
        89=>'围产保健登记卡生成状态',
        90=>'户籍地',
    ];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teamid','idtype','field90','doctorid','field2', 'field5', 'field11', 'field12', 'field15', 'field16', 'field31', 'field58', 'field60', 'field61', 'field63', 'field72', 'field83','familyid', 'field3', 'field7', 'field8', 'field9', 'field13', 'field14', 'field17', 'field18', 'field19', 'field20', 'field21', 'field22', 'field23', 'field24', 'field28', 'field29', 'field30', 'field32', 'field35', 'field39', 'field40', 'field41', 'field43', 'field45', 'field46', 'field47', 'field48', 'field49', 'field50', 'field51', 'field52', 'field53', 'field54', 'field55', 'field56', 'field57', 'field62', 'field64', 'field65', 'field67', 'field68', 'field70', 'field74', 'field76', 'field77', 'field78', 'field80', 'field81', 'field84', 'field85', 'field87', 'field88', 'source', 'isupdate'], 'integer'],
            [['field6', 'field38','safe','field4'], 'safe'],
            [['field71', 'field73'], 'number'],
            [['field0', 'field75'], 'string', 'max' => 20],
            //[['field4'], 'string', 'max' => 18],
            [['field10', 'field42','field1','field36'], 'string', 'max' => 40],
            [['field25', 'field27', 'field44', 'field82'], 'string', 'max' => 30],
            [['field26', 'field33'], 'string', 'max' => 5],
            [['field34'], 'string', 'max' => 50],
            [['field59'], 'string', 'max' => 60],
            [['field66', 'field79', 'field86'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'familyid' => 'Familyid',
            'field0' => '条形码',
            'field1' => '产妇姓名',
            'field2' => '出生日期',
            'field3' => '证件类型',
            'field4' => '证件号码',
            'field5' => '建册日期',
            'field6' => '联系电话',
            'field7' => '户口所在地-省',
            'field8' => '居住地-省',
            'field9' => '居住地-市',
            'field10' => '居住地-详细',
            'field11' => '末次月经',
            'field12' => '初诊日期',
            'field13' => '初检孕周',
            'field14' => 'B超核实',
            'field15' => '核实后预产期',
            'field16' => '核实后末次月经',
            'field17' => '建册孕周',
            'field18' => '孕次',
            'field19' => '产次',
            'field20' => '自然流产次数',
            'field21' => '药物及人工流产次数',
            'field22' => '叶酸使用情况',
            'field23' => '叶酸服用是否规律',
            'field24' => '家族史',
            'field25' => '家族史-详细',
            'field26' => '既往史',
            'field27' => '既往史-详细',
            'field28' => '死胎数',
            'field29' => '死产数',
            'field30' => '新生儿死亡数',
            'field31' => '上次分娩日期',
            'field32' => '上次分娩方式',
            'field33' => '妊娠期合并症、并发症',
            'field34' => '围产高危',
            'field35' => '高危级别',
            'field36' => '丈夫姓名',
            'field37' => '丈夫证件号',
            'field38' => '丈夫联系电话',
            'field39' => '丈夫户口地址-省',
            'field40' => '丈夫居住地-省',
            'field41' => '丈夫居住地-市',
            'field42' => '丈夫居住地-详细',
            'field43' => '丈夫年龄',
            'field44' => '产检地点',
            'field45' => '本地农业户籍',
            'field46' => '农村孕产妇住院分娩补助',
            'field47' => '产前检查补助',
            'field48' => '高危',
            'field49' => '分娩',
            'field50' => '产后访视',
            'field51' => '访视次数',
            'field52' => '首检标识',
            'field53' => '是否出院',
            'field54' => '产前诊断',
            'field55' => '超声筛查',
            'field56' => '血生化筛查',
            'field57' => '妊娠状态',
            'field58' => '出院日期',
            'field59' => '当前高危因素',
            'field60' => '预约时间',
            'field61' => '追访日期',
            'field62' => '追访次数',
            'field63' => '录入日期',
            'field64' => '是否发放艾滋病病毒(HIV)抗体免费检测卡',
            'field65' => '符合单独二孩政策的第二胎',
            'field66' => '当前高危因素（20180312之前版）',
            'field67' => '高危级别（20180312之前版）',
            'field68' => '本次妊娠为',
            'field70' => '身高',
            'field71' => '孕前体重',
            'field72' => '分娩日期',
            'field73' => 'BMI指数',
            'field74' => 'BMI分类',
            'field75' => '收缩压',
            'field76' => '分娩机构',
            'field77' => '舒张压',
            'field78' => '发放国家母子健康手册',
            'field79' => '高危因素（2018新版）',
            'field80' => '使用国家母子健康手册',
            'field81' => '高危级别（2018新版）',
            'field82' => '产检机构',
            'field83' => '产检日期',
            'field84' => '产时产后并发症（蓝球）',
            'field85' => '传染病（紫球）',
            'field86' => '地段初筛筛查结果详细（2018版）',
            'field87' => '地段初筛筛查结果',
            'field88' => '档案来源',
            'source' => '导入来源',
            'isupdate' => '是否新增',
            'createtime'=>'创建时间'
        ];
    }
    public function beforeSave($insert)
    {
        if($insert){
            $this->createtime=time();
            $this->isupdate=1;
        }
        if($this->source && !$this->doctorid){
            $this->doctorid=$this->source;
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
    public static function inputData($value,$hospital)
    {
        //SUBSTRING(ordersn, 9,2)
        $field4=substr($value['field4'],-4);
        $preg=\common\models\Pregnancy::find()->where(['field1'=>$value['field1']])->filterWhere(['SUBSTRING(field4, -4)'=>$field4])->one();

        if($preg){
            $return =1;
        }else{
            $return =2;
        }
        $preg=$preg?$preg:new \common\models\Pregnancy();
        $parent=UserParent::find()
            ->filterWhere(['SUBSTRING(mother_id, -4)'=>$field4])
            ->andWhere(['mother'=>$value['field1']])
            ->one();
        $value['familyid']=$parent?$parent->userid:0;
        $value['field2']=$value['field2']?strtotime(substr($value['field2'],0,10)):0;
        $value['field5']=$value['field5']?strtotime(substr($value['field5'],0,10)):0;
        $field7=array_search($value['field7'],Area::$province);
        $value['field7']=$field7?$field7:0;
        $value['field11']=$value['field11']?strtotime(substr($value['field11'],0,10)):0;
        $value['field15']=$value['field15']?strtotime(substr($value['field15'],0,10)):0;
        $value['field16']=$value['field16']?strtotime(substr($value['field16'],0,10)):0;
        $value['field49']=$value['field49']=='是'?1:0;
        $value['field89']=$value['field89']=='是'?1:0;

        $value['field61']=$value['field61']?strtotime(substr($value['field61'],0,10)):0;
        $value['field70']=floor($value['field70']);


        $value['field62']=$value['field62']?$value['field62']:0;

        $field8=array_search($value['field8'],Area::$province);
        $value['field8']=$field8?$field8:0;

        $field9=array_search($value['field9'],Area::$province);
        $value['field9']=$field9?$field9:0;


        $field39=array_search($value['field39'],Area::$province);
        $value['field39']=$field39?$field39:0;

        $field74=array_search($value['field74'],\common\models\Pregnancy::$bmi);
        $value['field74']=$field74?$field74:0;

        $field81=array_search($value['field81'],\common\models\Pregnancy::$field81);
        $value['field81']=$field81?$field81:0;


        $field90=array_search($value['field90'],\common\models\Pregnancy::$field90);
        $value['field90']=$field90?$field90:0;

        $value['source']=$hospital;
        $value['doctorid']=$hospital;
        $preg->load(['Pregnancy'=>$value]);

        if(!$preg->save()){
            var_dump($preg->firstErrors);
            return false;
        }
        return $return;
    }
}
