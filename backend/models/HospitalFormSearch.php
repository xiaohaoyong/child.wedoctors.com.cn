<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\HospitalForm;

/**
 * HospitalFormSearch represents the model behind the search form about `\common\models\HospitalForm`.
 */
class HospitalFormSearch extends HospitalForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sign1', 'sign2', 'sign3', 'date', 'ratio1', 'ratio2', 'appoint_num', 'other_appoint_num', 'doctorid'], 'integer'],
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
        $query = HospitalForm::find();

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
            'sign1' => $this->sign1,
            'sign2' => $this->sign2,
            'sign3' => $this->sign3,
            'date' => $this->date,
            'ratio1' => $this->ratio1,
            'ratio2' => $this->ratio2,
            'appoint_num' => $this->appoint_num,
            'other_appoint_num' => $this->other_appoint_num,
            'doctorid' => $this->doctorid,
        ]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
