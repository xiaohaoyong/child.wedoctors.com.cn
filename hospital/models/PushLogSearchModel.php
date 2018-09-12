<?php

namespace hospital\models;

use common\models\UserDoctor;
use common\models\WeOpenid;
use hospital\models\behaviors\UserParent;
use hospital\models\user\ChildInfo;
use common\models\User;
use hospital\controllers\ChildInfoController;
use hospital\models\user\DoctorParent;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ChildInfoSearchModel represents the model behind the search form about `app\models\user\ChildInfo`.
 */
class PushLogSearchModel extends Model
{
    public $sdatetime;
    public $edatetime;

    public $noChild;

    public $data=[
        1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,
    ];


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sdatetime','edatetime'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'sdatetime' => '日期',
            'edatetime' => '~',

        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->load($params);
        $hospitalid=Yii::$app->user->identity->hospital;
        $doctorid=UserDoctor::findOne(['hospitalid'=>$hospitalid])->userid;

        if(!$this->sdatetime){
            $this->sdatetime=date('Y-m-d');
        }
        if(!$this->edatetime){
            $this->edatetime=date('Y-m-d');
        }
        $sdate=date('Ymd',strtotime($this->sdatetime));
        $edate=date('Ymd',strtotime($this->edatetime));



        $stime=strtotime($this->sdatetime." 00:00:00");
        $etime=strtotime($this->edatetime." 23:59:59");

        $this->data[1]=WeOpenid::find()->where(['level'=>0])->andWhere(['doctorid'=>$doctorid])->andWhere(['>','createtime',$stime])->andWhere(['<','createtime',$etime])->count();


        $this->noChild=\common\models\DoctorParent::find()
            ->leftJoin('child_info', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andWhere(['>','doctor_parent.createtime',$stime])
            ->andWhere(['<','doctor_parent.createtime',$etime])
            ->andWhere(['`doctor_parent`.`doctorid`' => $doctorid])
            ->andWhere(['child_info.userid'=>null])
            ->all();
        $this->data[4]=count($this->noChild);

        $redis=Yii::$app->rdmp;


        for($i=$sdate;$i<=$edate;$i=date('Ymd',strtotime($i." +1 day"))){

            $this->data[3]+=$redis->ZSCORE("RegisterUnfinished".$hospitalid.$i,"total1ok");
            $this->data[6]+=$redis->ZSCORE("RegisterUnfinished".$hospitalid.$i,"total2ok");
            $this->data[2]+=$redis->ZSCORE("RegisterUnfinished".$hospitalid.$i,"total1");
            $this->data[5]+=$redis->ZSCORE("RegisterUnfinished".$hospitalid.$i,"total2");

        }



        return $this;
    }
}
