<?php

namespace hospital\models;

use common\models\ChildInfo;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Appoint;

/**
 * AppointSearchModels represents the model behind the search form about `\common\models\Appoint`.
 */
class AppointSearchModels extends Appoint
{
    public $child_name;
    public $appoint_dates = '';
    public $createtimes = '';
    public $createtimes_end = '';

    public $appoint_dates_end = '';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mode','vaccine','state','cancel_type','id', 'userid', 'doctorid', 'createtime', 'appoint_time', 'appoint_date', 'type', 'childid', 'phone'], 'integer'],
            [['appoint_dates_end','child_name', 'appoint_dates', 'createtimes', 'createtimes_end'], 'string']
        ];
    }
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $return = parent::attributeLabels();
        $return ['child_name'] = '儿童姓名';
        $return ['appoint_dates'] = '预约日期';
        $return ['appoint_dates_end'] = '~';
        $return ['createtimes'] = '创建时间';
        $return ['createtimes_end'] = '~';
        return $return;
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



        $query = Appoint::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $doctorid = \common\models\UserDoctor::findOne(['hospitalid' => \Yii::$app->user->identity->hospital]);

        $hospitalid = Yii::$app->user->identity->hospital;
        if ($this->child_name) {
            $childids = ChildInfo::find()->select('id')->andWhere(['source' => $hospitalid])->andWhere(['name' => $this->child_name])->column();
            if ($childids) {
                $query->andFilterWhere(['in', 'childid', $childids]);
            } else {
                $query->andFilterWhere(['in', 'childid', [0]]);
            }
        }

        if($this->appoint_dates){
            $query->andFilterWhere(['>=', 'appoint_date', strtotime($this->appoint_dates)]);
        }
        if($this->appoint_dates_end){
            $query->andFilterWhere(['<=', 'appoint_date', strtotime($this->appoint_dates_end)]);
        }
        if($this->createtimes){
            $query->andFilterWhere(['>=', 'createtime', strtotime($this->createtimes)]);
        }
        if($this->createtimes_end){
            $query->andFilterWhere(['<=', 'createtime', strtotime($this->createtimes_end)]);
        }
        if($this->appoint_date){
            $query->andWhere(['appoint_date'=>$this->appoint_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'userid' => $this->userid,
            'createtime' => $this->createtime,
            'appoint_time' => $this->appoint_time,
            'type' => $this->type,
            'phone' => $this->phone,
            'cancel_type'=>$this->cancel_type,
            'state'=>$this->state,
            'vaccine'=>$this->vaccine,
                        'mode'=>$this->mode

        ]);
        if(\Yii::$app->user->identity->hospital!=110565) {
            $query->andWhere(['doctorid' => $doctorid->userid]);
        }
        $query->orderBy([self::primaryKey()[0] => SORT_DESC]);

        return $dataProvider;
    }
}
