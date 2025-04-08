<?php

namespace common\models;

use common\helpers\WechatSendTmp;
use Yii;

/**
 * This is the model class for table "examination".
 *
 * @property int $id
 * @property int $childid 关联儿童
 * @property string $field1 儿童姓名
 * @property string $field2 实足年龄_岁
 * @property string $field3 实足年龄_月
 * @property string $field4 体检日期
 * @property string $field5 年龄别体重评价
 * @property string $field6 年龄别身长评价
 * @property string $field7 身长别体重评价
 * @property string $field8 体检医生签名
 * @property string $field9 体检机构
 * @property string $field10 腹部
 * @property string $field11 腹部异常信息
 * @property string $field12 贫血评价
 * @property string $field13 佝偻病评价
 * @property string $field14 佝偻病活动期
 * @property string $field15 营养不良评价
 * @property string $field16 肥胖评价（依肥胖度划分）
 * @property string $field17 肥胖评价（标准差五分法）
 * @property string $field18 健康评价_其他
 * @property string $field19 出生日期
 * @property string $field20 BMI值
 * @property string $field21 24小时母乳喂养
 * @property string $field22 前囟
 * @property string $field23 面色
 * @property string $field24 矫治颗数
 * @property string $field25 两次随访间患病情况
 * @property string $field26 耳外观
 * @property string $field27 耳外观异常信息
 * @property string $field28 眼外观
 * @property string $field29 眼外观异常信息
 * @property string $field30 步态
 * @property string $field31 步态异常信息
 * @property string $field32 性别
 * @property string $field33 肛门/外生殖器
 * @property string $field34 肛门/外生殖器异常信息
 * @property string $field35 发育评估
 * @property string $field36 指导内容
 * @property string $field37 先天性心脏病可疑征象
 * @property string $field38 心脏
 * @property string $field39 先天性心脏病可疑征象阳性内容
 * @property string $field40 身长（cm）
 * @property string $field41 血红蛋白值
 * @property string $field42 先天性髋关节脱位可疑征象
 * @property string $field43 发育性髋关节发育不良可疑征象阳性内容
 * @property string $field44 四肢
 * @property string $field45 四肢异常信息
 * @property string $field46 肺脏
 * @property string $field47 肺脏异常信息
 * @property string $field48 口腔
 * @property string $field49 口腔异常信息
 * @property string $field50 脐部
 * @property string $field51 脐部异常信息
 * @property string $field52 下次体检日期
 * @property string $field53 肥胖度评价
 * @property string $field54 户外活动（小时/日）
 * @property string $field55 经皮测氧饱和度
 * @property string $field56 经皮测氧饱和度异常_右手
 * @property string $field57 经皮测氧饱和度异常_足
 * @property string $field58 体格检查_其他
 * @property string $field59 转诊建议
 * @property string $field60 转诊原因
 * @property string $field61 转诊机构及科室
 * @property string $field62 佝偻病体征
 * @property string $field63 佝偻病症状
 * @property string $field64 新生儿产科听力筛查情况
 * @property string $field65 皮肤
 * @property string $field66 皮肤异常信息
 * @property string $field67 服用铁剂情况
 * @property string $field68 出牙数（颗）
 * @property string $field69 服用维生素D
 * @property string $field70 体重（kg）
 * @property string $field71 听力筛查
 * @property string $field72 左耳听力
 * @property string $field73 右耳听力
 * @property string $field74 OAE耳声发射请选择
 * @property string $field75 扁桃体
 * @property string $field76 沙眼
 * @property string $field77 视力左
 * @property string $field78 视力右
 * @property string $field79 心脏异常
 * @property string $field80 头围
 * @property string $field81 听力筛查方法
 * @property string $field82 本次体检属于
 * @property string $field83 体检费用
 * @property string $field84 龋齿颗数
 * @property string $field85 户口
 * @property string $field86 发放中医药健康指导宣传材料
 * @property string $field87 发放年龄
 * @property string $field88 使用国家母子健康手册
 * @property string $field89 预警征象
 * @property string $field90 预警征象描述
 * @property string $field91 心理行为问题
 * @property string $field92 心理行为问题描述
 * @property string $isupdate 是否发送更新提醒
 * @property int $source
 */
class Examination extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'examination';
    }
    public static $field=[
        1=>'儿童姓名',
        2=>'实足年龄_岁',
        3=>'实足年龄_月',
        4=>'体检日期',
        5=>'年龄别体重评价',
        6=>'年龄别身长评价',
        7=>'身长别体重评价',
        8=>'体检医生签名',
        9=>'体检机构',
        10=>'腹部',
        11=>'腹部异常信息',
        12=>'贫血评价',
        13=>'佝偻病评价',
        14=>'佝偻病活动期',
        15=>'营养不良评价',
        16=>'肥胖评价（依肥胖度划分）',
        17=>'肥胖评价（标准差五分法）',
        18=>'健康评价_其他',
        19=>'出生日期',
        20=>'BMI值',
        21=>'24小时母乳喂养',
        22=>'前囟',
        23=>'面色',
        24=>'矫治颗数',
        25=>'两次随访间患病情况',
        26=>'耳外观',
        27=>'耳外观异常信息',
        28=>'眼外观',
        29=>'眼外观异常信息',
        30=>'步态',
        31=>'步态异常信息',
        32=>'性别',
        33=>'肛门/外生殖器',
        34=>'肛门/外生殖器异常信息',
        35=>'发育评估',
        36=>'指导内容',
        37=>'先天性心脏病可疑征象',
        38=>'心脏',
        39=>'先天性心脏病可疑征象阳性内容',
        40=>'身长（cm）',
        41=>'血红蛋白值',
        42=>'先天性髋关节脱位可疑征象',
        43=>'发育性髋关节发育不良可疑征象阳性内容',
        44=>'四肢',
        45=>'四肢异常信息',
        46=>'肺脏',
        47=>'肺脏异常信息',
        48=>'口腔',
        49=>'口腔异常信息',
        50=>'脐部',
        51=>'脐部异常信息',
        52=>'下次体检日期',
        53=>'肥胖度评价',
        54=>'户外活动（小时/日）',
        55=>'经皮测氧饱和度',
        56=>'经皮测氧饱和度异常_右手',
        57=>'经皮测氧饱和度异常_足',
        58=>'体格检查_其他',
        59=>'转诊建议',
        60=>'转诊原因',
        61=>'转诊机构及科室',
        62=>'佝偻病体征',
        63=>'佝偻病症状',
        64=>'新生儿产科听力筛查情况',
        65=>'皮肤',
        66=>'皮肤异常信息',
        67=>'服用铁剂情况',
        68=>'出牙数（颗）',
        69=>'服用维生素D',
        70=>'体重（kg）',
        71=>'听力筛查',
        72=>'左耳听力',
        73=>'右耳听力',
        74=>'OAE耳声发射请选择',
        75=>'扁桃体',
        76=>'沙眼',
        77=>'视力左',
        78=>'视力右',
        79=>'心脏异常',
        80=>'头围',
        81=>'听力筛查方法',
        82=>'本次体检属于',
        83=>'体检费用',
        84=>'龋齿颗数',
        85=>'户口',
        86=>'发放中医药健康指导宣传材料',
        87=>'发放年龄',
        88=>'使用国家母子健康手册',
        89=>'预警征象',
        90=>'预警征象描述',
        91=>'心理行为问题',
        92=>'心理行为问题描述',
        93=>'儿童保健手册id',
        94=>'基础档案id',
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['childid', 'source', 'isupdate', 'createtime'], 'integer'],
            [['field1', 'field2', 'field3', 'field4', 'field5', 'field6', 'field7', 'field8', 'field9', 'field10', 'field11', 'field12', 'field13', 'field14', 'field15', 'field16', 'field17', 'field18', 'field19', 'field20', 'field21', 'field22', 'field23', 'field24', 'field25', 'field26', 'field27', 'field28', 'field29', 'field30', 'field31', 'field32', 'field33', 'field34', 'field35', 'field36', 'field37', 'field38', 'field39', 'field40', 'field41', 'field42', 'field43', 'field44', 'field45', 'field46', 'field47', 'field48', 'field49', 'field50', 'field51', 'field52', 'field53', 'field54', 'field55', 'field56', 'field57', 'field58', 'field59', 'field60', 'field61', 'field62', 'field63', 'field64', 'field65', 'field66', 'field67', 'field68', 'field69', 'field70', 'field71', 'field72', 'field73', 'field74', 'field75', 'field76', 'field77', 'field78', 'field79', 'field80', 'field81', 'field82', 'field83', 'field84', 'field85', 'field86', 'field87', 'field88', 'field89', 'field90', 'field91', 'field92', 'field93', 'field94'], 'safe'],
        ];

    }

    public function getChild()
    {
        return $this->hasOne(ChildInfo::className(),['id'=>'childid']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'childid' => '关联儿童',
            'field1' => '儿童姓名',
            'field2' => '实足年龄_岁',
            'field3' => '实足年龄_月',
            'field4' => '体检日期',
            'field5' => '年龄别体重评价',
            'field6' => '年龄别身长评价',
            'field7' => '身长别体重评价',
            'field8' => '体检医生签名',
            'field9' => '体检机构',
            'field10' => '腹部',
            'field11' => '腹部异常信息',
            'field12' => '贫血评价',
            'field13' => '佝偻病评价',
            'field14' => '佝偻病活动期',
            'field15' => '营养不良评价',
            'field16' => '肥胖评价（依肥胖度划分）',
            'field17' => '肥胖评价（标准差五分法）',
            'field18' => '健康评价_其他',
            'field19' => '出生日期',
            'field20' => 'BMI值',
            'field21' => '24小时母乳喂养',
            'field22' => '前囟',
            'field23' => '面色',
            'field24' => '矫治颗数',
            'field25' => '两次随访间患病情况',
            'field26' => '耳外观',
            'field27' => '耳外观异常信息',
            'field28' => '眼外观',
            'field29' => '眼外观异常信息',
            'field30' => '步态',
            'field31' => '步态异常信息',
            'field32' => '性别',
            'field33' => '肛门/外生殖器',
            'field34' => '肛门/外生殖器异常信息',
            'field35' => '发育评估',
            'field36' => '指导内容',
            'field37' => '先天性心脏病可疑征象',
            'field38' => '心脏',
            'field39' => '先天性心脏病可疑征象阳性内容',
            'field40' => '身长（cm）',
            'field41' => '血红蛋白值',
            'field42' => '先天性髋关节脱位可疑征象',
            'field43' => '发育性髋关节发育不良可疑征象阳性内容',
            'field44' => '四肢',
            'field45' => '四肢异常信息',
            'field46' => '肺脏',
            'field47' => '肺脏异常信息',
            'field48' => '口腔',
            'field49' => '口腔异常信息',
            'field50' => '脐部',
            'field51' => '脐部异常信息',
            'field52' => '下次体检日期',
            'field53' => '肥胖度评价',
            'field54' => '户外活动（小时/日）',
            'field55' => '经皮测氧饱和度',
            'field56' => '经皮测氧饱和度异常_右手',
            'field57' => '经皮测氧饱和度异常_足',
            'field58' => '体格检查_其他',
            'field59' => '转诊建议',
            'field60' => '转诊原因',
            'field61' => '转诊机构及科室',
            'field62' => '佝偻病体征',
            'field63' => '佝偻病症状',
            'field64' => '新生儿产科听力筛查情况',
            'field65' => '皮肤',
            'field66' => '皮肤异常信息',
            'field67' => '服用铁剂情况',
            'field68' => '出牙数（颗）',
            'field69' => '服用维生素D',
            'field70' => '体重（kg）',
            'field71' => '听力筛查',
            'field72' => '左耳听力',
            'field73' => '右耳听力',
            'field74' => 'OAE耳声发射请选择',
            'field75' => '扁桃体',
            'field76' => '沙眼',
            'field77' => '视力左',
            'field78' => '视力右',
            'field79' => '心脏异常',
            'field80' => '头围',
            'field81' => '听力筛查方法',
            'field82' => '本次体检属于',
            'field83' => '体检费用',
            'field84' => '龋齿颗数',
            'field85' => '户口',
            'field86' => '发放中医药健康指导宣传材料',
            'field87' => '发放年龄',
            'field88' => '使用国家母子健康手册',
            'field89' => '预警征象',
            'field90' => '预警征象描述',
            'field91' => '心理行为问题',
            'field92' => '心理行为问题描述',
            'field93' => '儿童保健手册id',
            'field94' => '基础档案id',

            'isupdate'=>'是否通知',
            'source' => 'Source',
            'createtime'=>'导入时间',
        ];
    }
    public function beforeSave($insert)
    {
        if($insert){
            $this->isupdate=1;
            $this->createtime=time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
    public static function inputData($row,$hospitalid)
    {
        if(!isset($row['field2'])){
            return 3;
        }
        //if($row['field4']<'2018-01-01 00:00:00'){ echo "end\n";return;}

        //$row['field4'] = substr($row['field4'], 0, strlen($row['field4']) - 11);
        if(!isset($row['field4']))
        {
            $row['field4'] = '';
        }

        $ex = self::find()->andFilterWhere(['field1' => $row['field1']])
            ->andFilterWhere(['field2' => $row['field2']])
            ->andFilterWhere(['field3' => $row['field3']])
            ->andFilterWhere(['field4' => $row['field4']])
            ->andFilterWhere(['source' => $hospitalid])
            ->andFilterWhere(['field19' => $row['field19']])->one();
        //if($ex){ echo "jump\n";return 'jump';}
        if($ex){
            $return = 1;
        }else{
            $return = 2;
            $ex=new Examination();
        }

        if(isset($row['field93'])) {
            $child = ChildInfo::find()->andFilterWhere(['eid' => trim($row['field93'])])
                ->andFilterWhere(['source' => $hospitalid])
                ->one();
        }
        if(!$child) {
            $child = ChildInfo::find()->andFilterWhere(['name' => trim($row['field1'])])
                ->andFilterWhere(['birthday' => strtotime($row['field19'])])
                ->andFilterWhere(['source' => $hospitalid])
                ->one();
        }
        echo $row['field1'];
        $row['source']=$hospitalid;



        if (!$child) {
            echo "--儿童不存在";
            // $childData['childid'] = 0;
        } else {
            echo "--儿童存在";
            $row['childid'] = $child->id;
            if($return==2){
                $login = UserLogin::find()->where(['!=','openid',''])->andWhere(['userid'=>$child->userid])->one();
                if($child && $login && $login->openid){
                    $return = \Yii::$app->beanstalk
                        ->putInTube('exaupdate', ['childid'=>$child->id]);
                }
            }
        }

        $ex->load(['Examination'=>$row]);
        $ex->save();
        if ($ex->firstErrors) {
            echo "error";
            var_dump($ex->firstErrors);
        }
        echo "\n";
        return $return;
    }

}
