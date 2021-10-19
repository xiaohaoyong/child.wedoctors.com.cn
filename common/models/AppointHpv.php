<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "appoint_hpv".
 *
 * @property int $id
 * @property string $name 预约人姓名
 * @property int $phone 预约人联系电话
 * @property string $date 接种日期
 * @property int $state 状态
 * @property int $cratettime 创建时间
 * @property int $userid 用户ID
 * @property int $doctorid 预约社区
 * @property int $vid 预约疫苗
 */
class AppointHpv extends \yii\db\ActiveRecord
{
    public static $stateText = [0 => '审核中', 1 => '已通过', 2 => '未通过'];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appoint_hpv';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'userid', 'doctorid', 'vid', 'img'], 'required'],
            [['phone', 'state', 'cratettime', 'userid', 'doctorid', 'vid'], 'integer'],
            [['date', 'img'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '预约人姓名',
            'phone' => '预约人联系电话',
            'date' => '接种日期',
            'state' => '状态',
            'cratettime' => '创建时间',
            'userid' => '用户ID',
            'doctorid' => '预约社区',
            'vid' => '预约疫苗',
            'img' => '所属辖区的本人居委会证明'
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->cratettime = time();
            $this->state = 0;
            $this->date = 0;
        } else {
            if (($this->date < '1990-01-01' || !$this->date) && $this->state == 1) {
                $Ah = AppointHpv::find()->where(['doctorid' => Yii::$app->user->identity->doctorid])
                    ->andWhere(['state' => 1])
                    ->andWhere(['>', 'date', date('Y-m-d')])
                    ->orderBy('date desc')
                    ->one();
                $startDate=date('Y-m-d');
                if ($Ah) {
                    $endDayCount = AppointHpv::find()->where(['doctorid' => Yii::$app->user->identity->doctorid])
                        ->andWhere(['state' => 1])
                        ->andWhere(['date' => $Ah->date])
                        ->count();
                    $week = date('w', strtotime($Ah->date));
                    $key = 'week' . $week;

                    $aw = AppointHpvSetting::find()
                        ->where(['doctorid' => Yii::$app->user->identity->doctorid])
                        ->one();
                    if($aw) {
                        $weekCount = $aw->$key;
                        if ($endDayCount < $weekCount) {
                            $this->date = $Ah->date;
                            return parent::beforeSave($insert); // TODO: Change the autogenerated stub
                        } else {
                            $startDate = $Ah->date;
                        }
                    }else{
                        $this->addError('date','自动分配失败！您没有设置号源');
                        return false;
                    }
                }
                while ($startDate) {
                    $d = strtotime( '+1 day',strtotime($startDate));
                    $startDate=date('Y-m-d',$d);
                    //判断周末
                    $w=date('w',$d);
                    if($w==6 || $w==0){
                        continue;
                    }
                    //判断法定假日
                    if(!in_array(date('Y-m-d',$d), HospitalAppoint::$holiday)){
                        $this->date=date('Y-m-d',$d);
                        break;
                    }else{
                        continue;
                    }
                }
            }
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}