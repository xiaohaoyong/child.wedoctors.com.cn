<?php

namespace hospital\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\HealthRecordsSchool;

/**
 * HealthRecordsSchoolSearch represents the model behind the search form about `\common\models\HealthRecordsSchool`.
 */
class HealthRecordsSchoolSearch extends HealthRecordsSchool
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'doctorid'], 'integer'],
            [['name', 'sign1', 'sign2', 'doctor_name'], 'safe'],
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
        $query = HealthRecordsSchool::find();

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
            ->andFilterWhere(['like', 'sign1', $this->sign1])
            ->andFilterWhere(['like', 'sign2', $this->sign2])
            ->andFilterWhere(['like', 'doctor_name', $this->doctor_name]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
