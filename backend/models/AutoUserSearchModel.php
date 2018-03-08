<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AutoUser;

/**
 * AutoUserSearchModel represents the model behind the search form about `backend\models\AutoUser`.
 */
class AutoUserSearchModel extends AutoUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'createtime', 'lasttime'], 'integer'],
            [['username', 'password', 'auth_key', 'accessToken'], 'safe'],
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
        $query = AutoUser::find();

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
            'createtime' => $this->createtime,
            'lasttime' => $this->lasttime,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'accessToken', $this->accessToken]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
