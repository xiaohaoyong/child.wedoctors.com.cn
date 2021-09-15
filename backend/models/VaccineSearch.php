<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Vaccine;

/**
 * VaccineSearch represents the model behind the search form about `common\models\Vaccine`.
 */
class VaccineSearch extends Vaccine
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'source'], 'integer'],
            [['disease', 'adverseReactions', 'contraindications', 'diseaseHarm', 'dealFlow', 'name', 'intervalName'], 'safe'],
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
        $query = Vaccine::find();

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
            'source' => $this->source,
        ]);

        $query->andFilterWhere(['like', 'disease', $this->disease])
            ->andFilterWhere(['like', 'adverseReactions', $this->adverseReactions])
            ->andFilterWhere(['like', 'contraindications', $this->contraindications])
            ->andFilterWhere(['like', 'diseaseHarm', $this->diseaseHarm])
            ->andFilterWhere(['like', 'dealFlow', $this->dealFlow])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'intervalName', $this->intervalName]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
