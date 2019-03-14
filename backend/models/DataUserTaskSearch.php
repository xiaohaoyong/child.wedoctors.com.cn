<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DataUserTask;

/**
 * DataUserTaskSearch represents the model behind the search form about `\common\models\DataUserTask`.
 */
class DataUserTaskSearch extends DataUserTask
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'datauserid', 'createtime', 'num', 'state', 'fd'], 'integer'],
            [['note', 'result'], 'safe'],
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
        $query = DataUserTask::find();

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
            'datauserid' => $this->datauserid,
            'createtime' => $this->createtime,
            'num' => $this->num,
            'state' => $this->state,
            'fd' => $this->fd,
        ]);

        $query->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'result', $this->result]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
