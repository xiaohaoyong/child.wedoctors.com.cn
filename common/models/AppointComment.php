<?php
/**
 * Created by PhpStorm.
 * User: xywy
 * Date: 2022/10/24
 * Time: 13:47
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "appoint_comment".
 *
 * @property int $id
 * @property int $aid 预约订单id
 * @property int $userid	用户id
 * @property int $doctorid	医生id
 * @property int $createtime 时间
 * @property int $is_envir 对环境是否满意，数字代表星级	
 * @property int $is_envir_on 环境评价低于三星：1-医院味道不好 2-医院地很脏 3-医院整体很暗
 * @property int $is_process 对就诊流程是否满意，数字代表星级
 * @property int $is_process_on 流程评价低于三星：1-缺少引导 2-流程不清晰
 * @property int $is_staff 对工作人员是否满意，数字代表星级
 * @property int $is_staff_on 对工作人员低于三星：1-敷衍了事 2-答非所问 3-没有实质性建议
 * @property int $is_jue 是否解决1未解决2已解决
 */
class AppointComment extends \yii\db\ActiveRecord
{
    //满意度
    public static $enverstaffArr = [
        1=>'1星',
        2=>'2星',
        3=>'3星',
        4=>'4星',
        5=>'5星'
    ];
	//对环境是否满意
    public static $envirArr = [
        1=>'医院味道不好',
        2=>'医院地很脏',
        3=>'医院整体很暗'
    ];
	//对流程是否满意
    public static $processArr = [
        1=>'缺少引导',
        2=>'流程不清晰'
    ];
	//对工作人员是否满意
    public static $staffArr = [
        1=>'敷衍了事',
        2=>'答非所问',
        3=>'没有实质性建议'
    ];
    //整体评价
    public static $allArr = [
        1=>'好评',
        2=>'中评',
        3=>'差评',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appoint_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid','doctorid', 'aid', 'is_envir', 'is_process','is_staff','is_rate'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'createtime' => '评价时间',
            'aid' => '预约订单ID',
            'userid' => '用户ID',
            'doctorid' => '预约社区医院',
            'is_envir' => '对环境是否满意',
            'is_process' => '对就诊流程是否满意',
            'is_staff' => '对工作人员是否满意',
            'startDate' => '开始时间',
            'endDate' => '结束时间',
            'county' => '县',
            'is_rate' => '整体评价',
        ];
    }
}