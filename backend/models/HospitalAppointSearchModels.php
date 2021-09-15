<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\HospitalAppoint;

/**
 * HospitalAppointSearchModels represents the model behind the search form about `\common\models\HospitalAppoint`.
 */
class HospitalAppointSearchModels extends HospitalAppoint
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'doctorid', 'cycle', 'delay', 'type', 'weeks'], 'integer'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = HospitalAppoint::find();

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
            'doctorid' => $this->doctorid,
            'cycle' => $this->cycle,
            'delay' => $this->delay,
            'type' => $this->type,
            'weeks' => $this->weeks,
        ]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
