<?php

namespace backend\models;

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
    public $ids;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state','cancel_type','id', 'userid', 'doctorid', 'createtime', 'appoint_time', 'appoint_date', 'type', 'childid', 'phone'], 'integer'],
            [['child_name', 'appoint_dates','ids'], 'string']
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
        $return ['ids'] = 'id';

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

        if($this->appoint_dates){
            $this->appoint_date=strtotime($this->appoint_dates);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($this->child_name) {
            $childids = ChildInfo::find()->select('id')->andWhere(['name' => $this->child_name])->column();
            if ($childids) {
                $query->andFilterWhere(['in', 'childid', $childids]);
            } else {
                $query->andFilterWhere(['in', 'childid', [0]]);
            }
        }


        if($this->ids){
            $query->andFilterWhere(['in','id',explode(',',$this->ids)]);
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'userid' => $this->userid,
            'doctorid' => $this->doctorid,
            'childid'=>$this->childid,
            'createtime' => $this->createtime,
            'appoint_time' => $this->appoint_time,
            'appoint_date' => $this->appoint_date,
            'type' => $this->type,
            'phone' => $this->phone,
            'cancel_type'=>$this->cancel_type,
            'state'=>$this->state,
        ]);
        $query->orderBy(['createtime' => SORT_DESC]);
        //echo $query->createCommand()->getRawSql();exit;
        return $dataProvider;
    }
}
