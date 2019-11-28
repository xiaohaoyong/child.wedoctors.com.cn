<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "points".
 *
 * @property int $id
 * @property int $userid 用户
 * @property int $createtime 时间
 * @property int $point 分数
 * @property int $source 来源
 * @property int $onlyid 唯一id
 */
class Points extends \yii\db\ActiveRecord
{
    public static $sourceText=[
        1=>'阅读指导',
        2=>'添加宝宝',
        3=>'阅读文章',
        4=>'评论文章',
        5=>'签到',
        6=>'文章分享',
        7=>'点赞',

    ];
    public static $sourcePointNum=[
        1=>5,
        2=>10,
        3=>2,
        4=>2,
        5=>2,
        6=>3,
        7=>2,
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'points';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'createtime', 'point', 'source','onlyid'], 'integer'],
            [['point', 'source'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => '用户',
            'createtime' => '时间',
            'point' => '分数',
            'source' => '来源',
        ];
    }

    public function addPoint($userid,$source,$onlyid=0,$extra=0){
        if(!$userid) return false;
        $point=self::$sourcePointNum[$source]+$extra;
        $start=strtotime(date('Y-m-d 00:00:00'));
        if($onlyid) {
            $pon = self::find()->where(['userid' => $userid])
                ->andWhere(['>', 'createtime', $start])
                ->andWhere(['onlyid' => $onlyid])
                ->andWhere(['source'=>$source])
                ->one();
            if ($pon) return false;
        }
        $total=self::find()->where(['userid'=>$userid])->andWhere(['>','createtime',$start])->andWhere(['>','point',0])->sum('point');
        if(($total+$point)>50){
            return 20001;
        }
        if(!$this->sourceLimit($userid,$source)){
            return 20002;
        }
        $this->userid=$userid;
        $this->source=$source;
        $this->onlyid=$onlyid;

        $this->point=self::$sourcePointNum[$source]+$extra;
        $this->save();
    }
    public function sourceLimit($userid,$source){
        $point=self::find()->where(['userid'=>$userid])->andWhere(['source'=>$source]);
        if($source==2 && $point->count()>=3){
            return false;
        }
        $start=strtotime(date('Y-m-d 00:00:00'));
        if($source==6 && $point->andWhere(['>','createtime',$start])->count()>=1){
            return false;
        }


        return true;
    }
    public function beforeSave($insert)
    {
        if($insert){
            $this->createtime=time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}