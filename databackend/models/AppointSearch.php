<?php

namespace databackend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Appoint;

/**
 * AppointSearch represents the model behind the search form of `\common\models\Appoint`.
 */
class AppointSearch extends Appoint
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'userid', 'doctorid', 'createtime', 'appoint_time', 'appoint_date', 'type', 'childid', 'phone', 'state', 'loginid', 'cancel_type', 'push_state', 'mode', 'vaccine'], 'integer'],
            [['remark'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'userid' => $this->userid,
            'doctorid' => $this->doctorid,
            'createtime' => $this->createtime,
            'appoint_time' => $this->appoint_time,
            'appoint_date' => $this->appoint_date,
            'type' => $this->type,
            'childid' => $this->childid,
            'phone' => $this->phone,
            'state' => $this->state,
            'loginid' => $this->loginid,
            'cancel_type' => $this->cancel_type,
            'push_state' => $this->push_state,
            'mode' => $this->mode,
            'vaccine' => $this->vaccine,
        ]);

        $query->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
