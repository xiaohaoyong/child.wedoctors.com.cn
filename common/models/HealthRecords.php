<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "health_records".
 *
 * @property int $id
 * @property int $field1 国籍
 * @property int $field2 民族
 * @property string $field3 家长姓名
 * @property int $field4 家长手机
 * @property int $field5 户籍
 * @property string $field5_text 户籍详细
 * @property string $field6 现住址
 * @property int $field7 血型
 * @property int $field8 医疗费用支付方式
 * @property string $field8_text 医疗费用支付方式详细
 * @property int $field9 身高
 * @property int $field10 体重
 * @property int $field11 腰围
 * @property int $field12 臀围
 * @property int $field13 视力左
 * @property int $field14 视力右
 * @property string $field15 身份证（护照/港澳台通行证）
 * @property int $field16 药物过敏史
 * @property string $field16_text 名称
 * @property int $field17 疾病史
 * @property string $field17_text 其他请注明
 * @property int $field18 手术外伤史
 * @property string $field18_text 名称
 * @property int $field19 输血情况
 * @property string $field19_text 时间
 * @property int $field20 残疾情况
 * @property string $field20_text 名称
 * @property int $field21 饮食类型
 * @property int $field22 饮食量
 * @property int $field23 锻炼
 * @property int $field24 频率
 * @property int $field25 类型
 * @property int $field26 每次
 * @property int $field27 睡眠类型
 * @property int $field28 睡眠时长
 * @property string $field29 学生姓名
 * @property string $field30 学校
 * @property string $field31 校医姓名
 * @property string $field32 校医电话
 * @property string $field33 家长签字
 * @property int $field39 传染病疫情防控指导
 * @property int $field35 儿童常见健康问题指导
 * @property int $field36 龋齿预防
 * @property int $field37 预防接种
 * @property int $field38 中医外治法防治青少年近视（自愿选择，非强制）
 * @property int $createtime 建档日期
 * @property int $doctorid  社区
 */
class HealthRecords extends \yii\db\ActiveRecord
{
    public static $field1Txt = [1 => '内地', 2 => '港澳台', 3 => '国外'];
    public static $field2Txt = [1 => '汉族', 2 => '少数民族'];
    public static $field5Txt = [1 => '北京', 2 => '其他'];
    public static $field7Txt = [1 => 'A', 2 => 'B', 3 => 'AB', 4 => 'O', 5 => '不详'];
    public static $field8Txt = [1 => '医保', 2 => '商业', 3 => '自费', 4 => '其他'];
    public static $field16Txt = [1 => '无', 2 => '有'];
    public static $field41Txt = [1 => '男', 2 => '女'];

    public static $field17Txt = [1 => '无', 2 => '有'];
    public static $field18Txt = [1 => '无', 2 => '有'];
    public static $field19Txt = [1 => '无', 2 => '有'];
    public static $field20Txt = [1 => '无', 2 => '有'];
    public static $field21Txt = [1 => '辛辣', 2 => '偏咸', 3 => '偏甜', 4 => '偏油', 5 => '嗜热食', 6 => '素食', 7 => '正常'];
    public static $field23Txt = [1 => '不锻炼', 2 => '规律', 3 => '偶尔'];
    public static $field24Txt = [1 => '每天', 2 => '>3次/周', 3 => '1-2次/周'];
    public static $field25Txt = [1 => '有氧', 2 => '无氧'];
    public static $field35Txt = [1 => ''];

    public static $field26Txt = [1 => '小于30分钟', 2 => '30-60分钟', 3 => '1小时以上'];
    public static $field27Txt = [1 => '睡眠困难', 2 => '入睡困难', 3 => '早睡', 4 => '梦游', 5 => '其他'];
    public static $vfieldVal = ['field5_text' => 2, 'field8_text' => 4, 'field16_text' => 2, 'field17_text' => 2, 'field18_text' => 2, 'field19_text' => 2, 'field20_text' => 2,];
    public static $vfield = ['field5_text' => 'field5', 'field8_text' => 'field8', 'field16_text' => 'field16', 'field17_text' => 'field17', 'field18_text' => 'field18', 'field19_text' => 'field19', 'field20_text' => 'field20',];

    public function scenarios()
    {
        return [
            'form1'=>['field29','field30','field34','field40','field41','field42','field3','field4','field6'],
            'form2'=>['field29','field30','field34','field40','field1','field42','field3','field4','field6','field7','field8','field9','field10','field11','field12','field13','field14','field15','field16','field17','field16_text','field17_text','field18_text','field19_text','field20_text','field18','field19','field20','field21','field22','field23','field24','field25','field26','field27','field28'],
            'default'=>['field29','field30','field34','field40','field1','field42','field3','field4','field6','field7','field8','field9','field10','field11','field12','field13','field14','field15','field16','field17','field16_text','field17_text','field18_text','field19_text','field20_text','field18','field19','field20','field21','field22','field23','field24','field25','field26','field27','field28'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'health_records';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['field1', 'field2', 'field3', 'field4', 'field5', 'field6', 'field7', 'field8', 'field9', 'field10', 'field11', 'field12', 'field13', 'field14', 'field15', 'field16', 'field17', 'field18', 'field19', 'field20', 'field21', 'field22', 'field23', 'field24', 'field25', 'field26', 'field27', 'field28', 'field29', 'field30', 'field34'], 'required','on' => 'form2'],
            [['field29','field30','field34','field40','field41','field42','field3','field4','field6'], 'required','on' => 'form1'],

            [['field1', 'field2', 'field4', 'field5', 'field7', 'field8', 'field16', 'field17', 'field18', 'field19', 'field20', 'field21', 'field22', 'field23', 'field24', 'field25', 'field26', 'field27', 'field28', 'createtime', 'doctorid', 'field39', 'field35', 'field36', 'field37', 'field38', 'field34', 'field40', 'field42','field30'], 'integer'],

            [['field9', 'field10', 'field11', 'field12', 'field13', 'field14'], 'number'],


            [['field9', 'field10', 'field11', 'field12', 'field13', 'field14'], 'compare', 'compareValue' => 251, 'operator' => '<'],

            [['field3'], 'string', 'max' => 30],
            [['field5_text', 'field8_text', 'field15', 'field16_text', 'field17_text', 'field18_text', 'field19_text', 'field20_text'], 'string', 'max' => 50],
            [['field6', 'field33'], 'string', 'max' => 100],
            [['field29', 'field31', 'field32'], 'string', 'max' => 20],
            [['field5_text', 'field8_text', 'field16_text', 'field17_text', 'field18_text', 'field19_text', 'field20_text'], 'validateField', 'skipOnEmpty' => false, 'skipOnError' => false],
            ['field4', 'match', 'pattern' => '/^[1][2345789][0-9]{9}$/'],
            [['field15', 'field41'], 'match', 'pattern' => '/^[1-9]\d{5}(19|20)\d{2}[01]\d[0123]\d\d{3}[xX\d]$|^([A-Z]\d{6,10}(\w1)?)$|^1[45][0-9]{7}$|([P|p|S|s]\d{7}$)|([S|s|G|g]\d{8}$)|([Gg|Tt|Ss|Ll|Qq|Dd|Aa|Ff]\d{8}$)|([H|h|M|m]\d{8,10})$/'],

            [['field1', 'field2', 'field4', 'field5', 'field7', 'field8', 'field9', 'field10', 'field11', 'field12', 'field13', 'field14', 'field42', 'field16', 'field17', 'field18', 'field19', 'field20','field21','field22','field23','field24','field25','field26','field27','field28','field30','field40','field35','field36','field37','field38','field39'], 'default', 'value' => 0],
            [['field3', 'field5_text', 'field6', 'field8_text', 'field15', 'field41', 'field16_text', 'field17_text', 'field18_text', 'field19_text', 'field20_text', 'field29', 'field34', 'field31', 'field32', 'field33'], 'default', 'value' => '']

        ];
    }

    public function validateField($attribute, $params)
    {
        $field = self::$vfield[$attribute];
        if ($this->$field == self::$vfieldVal[$attribute] && !$this->$attribute) {
            $this->addError($attribute, "请填写" . $this->getAttributeLabel($attribute));
        }

    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'field1' => '国籍',
            'field2' => '民族',
            'field3' => '家长姓名',
            'field4' => '家长手机',
            'field5' => '户籍',
            'field5_text' => '户籍详细',
            'field6' => '现住址',
            'field7' => '血型',
            'field8' => '医疗费用支付方式',
            'field8_text' => '医疗费用支付方式详细',
            'field9' => '身高(cm)',
            'field10' => '体重(kg)',
            'field11' => '腰围(cm)',
            'field12' => '臀围(cm)',
            'field13' => '视力左',
            'field14' => '视力右',
            'field15' => '身份证（护照/港澳台通行证）',
            'field41' => '学生身份证（护照/港澳台通行证）',
            'field42' => '学生性别',

            'field16' => '药物过敏史',
            'field16_text' => '名称',
            'field17' => '疾病史',
            'field17_text' => '其他请注明',
            'field18' => '手术外伤史',
            'field18_text' => '名称',
            'field19' => '输血情况',
            'field19_text' => '时间',
            'field20' => '残疾情况',
            'field20_text' => '名称',
            'field21' => '饮食类型',
            'field22' => '饮食量(克/次)',
            'field23' => '锻炼',
            'field24' => '频率',
            'field25' => '类型',
            'field26' => '每次',
            'field27' => '睡眠类型',
            'field28' => '睡眠时长（小时/天）',
            'field29' => '学生姓名',
            'field30' => '学校',
            'field31' => '校医姓名',
            'field32' => '校医电话',
            'field33' => '家长签字',
            'field34' => '年级',
            'field40' => '班级',
            'field39' => '传染病疫情防控指导',
            'field35' => '儿童常见健康问题指导',
            'field36' => '龋齿预防',
            'field37' => '预防接种',
            'field38' => '中医外治法防治青少年近视',
            'createtime' => '建档日期',
            'doctorid' => ' 社区',
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->createtime = time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
