<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearchModel represents the model behind the search form about `common\models\User`.
 */
class UserSearchModel extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'phone', 'createtime'], 'integer'],
            [['level', 'type', 'source'], 'safe'],
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
        $query = User::find();

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
            'phone' => $this->phone,
            'createtime' => $this->createtime,
        ]);

        $query->andFilterWhere(['like', 'level', $this->level])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'source', $this->source]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
