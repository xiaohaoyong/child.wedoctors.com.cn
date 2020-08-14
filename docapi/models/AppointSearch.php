<?php

namespace docapi\models;

use common\models\ChildInfo;
use common\models\Doctors;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Appoint;

/**
 * AppointSearchModels represents the model behind the search form about `\common\models\Appoint`.
 */
class AppointSearch extends Appoint
{
    public $child_name;
    public $appoint_dates = '';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state','cancel_type','id', 'userid', 'doctorid', 'createtime', 'appoint_time', 'appoint_date', 'type', 'childid', 'phone'], 'integer'],
            [['child_name', 'appoint_dates'], 'string']
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

        if($this->appoint_date){
            $this->appoint_date=$this->appoint_date;
        }else{
            $query->andFilterWhere(['>=', 'appoint_date', time()]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'userid' => $this->userid,
            'doctorid' => $this->doctorid,
            'createtime' => $this->createtime,
            'appoint_time' => $this->appoint_time,
            'appoint_date' => $this->appoint_date,
            'type' => $this->type,
            'phone' => $this->phone,
            'cancel_type'=>$this->cancel_type,
            'state'=>$this->state,
        ]);
        $query->orderBy([self::primaryKey()[0] => SORT_DESC]);
        //echo $query->createCommand()->getRawSql();exit;

        return $dataProvider;
    }
}
