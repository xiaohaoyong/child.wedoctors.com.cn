<?php

namespace hospital\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AppointExpert;

/**
 * AppointExpertSearch represents the model behind the search form about `common\models\AppointExpert`.
 */
class AppointExpertSearch extends AppointExpert
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'doctorid'], 'integer'],
            [['name', 'info'], 'safe'],
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
        $query = AppointExpert::find();

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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'info', $this->info]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
