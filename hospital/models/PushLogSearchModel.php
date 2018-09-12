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
    public $datetime;
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
            [['datetime'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'datetime' => '日期',
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
        if(!$this->datetime){
            $this->datetime=date('Y-m-d');
        }
        $date=date('Ymd',strtotime($this->datetime));

        $hospitalid=Yii::$app->user->identity->hospital;
        $doctorid=UserDoctor::findOne(['hospitalid'=>$hospitalid])->userid;

        $stime=strtotime($this->datetime." 00:00:00");
        $etime=strtotime($this->datetime." 23:59:59");

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
        $this->data[3]=$redis->ZSCORE("RegisterUnfinished".$hospitalid.$date,"total1ok");
        $this->data[6]=$redis->ZSCORE("RegisterUnfinished".$hospitalid.$date,"total2ok");
        $this->data[2]=$redis->ZSCORE("RegisterUnfinished".$hospitalid.$date,"total1");
        $this->data[5]=$redis->ZSCORE("RegisterUnfinished".$hospitalid.$date,"total2");

        return $this;
    }
}
