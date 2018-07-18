<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BabyTool;

/**
 * BabyToolSearch represents the model behind the search form about `common\models\BabyTool`.
 */
class BabyToolSearch extends BabyTool
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'createtime', 'period'], 'integer'],
            [['tag', 'content'], 'safe'],
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
        $query = BabyTool::find();

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
            'period' => $this->period,
        ]);

        $query->andFilterWhere(['like', 'tag', $this->tag])
            ->andFilterWhere(['like', 'content', $this->content]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
