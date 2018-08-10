<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserParent;

/**
 * UserParentSearch represents the model behind the search form about `common\models\UserParent`.
 */
class UserParentSearch extends UserParent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'mother_phone', 'father_phone', 'father_birthday', 'state', 'source', 'province', 'county', 'city'], 'integer'],
            [['mother', 'mother_id', 'father', 'address', 'field34', 'field33', 'field30', 'field29', 'field28', 'field12', 'field11', 'field1', 'fbirthday'], 'safe'],
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
        $query = UserParent::find();

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
            'userid' => $this->userid,
            'mother_phone' => $this->mother_phone,
            'father_phone' => $this->father_phone,
            'father_birthday' => $this->father_birthday,
            'state' => $this->state,
            'source' => $this->source,
            'province' => $this->province,
            'county' => $this->county,
            'city' => $this->city,
        ]);

        $query->andFilterWhere(['like', 'mother', $this->mother])
            ->andFilterWhere(['like', 'mother_id', $this->mother_id])
            ->andFilterWhere(['like', 'father', $this->father])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'field34', $this->field34])
            ->andFilterWhere(['like', 'field33', $this->field33])
            ->andFilterWhere(['like', 'field30', $this->field30])
            ->andFilterWhere(['like', 'field29', $this->field29])
            ->andFilterWhere(['like', 'field28', $this->field28])
            ->andFilterWhere(['like', 'field12', $this->field12])
            ->andFilterWhere(['like', 'field11', $this->field11])
            ->andFilterWhere(['like', 'field1', $this->field1])
            ->andFilterWhere(['like', 'fbirthday', $this->fbirthday]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
