<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ExaNoticeSetup;

/**
 * ExaNoticeSetupSearch represents the model behind the search form about `\common\models\ExaNoticeSetup`.
 */
class ExaNoticeSetupSearch extends ExaNoticeSetup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'hospitalid', 'level', 'end', 'month1', 'month2', 'month3', 'month4', 'month5', 'month6', 'month7', 'month8'], 'integer'],
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
        $query = ExaNoticeSetup::find();

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
            'hospitalid' => $this->hospitalid,
            'level' => $this->level,
            'end' => $this->end,
            'month1' => $this->month1,
            'month2' => $this->month2,
            'month3' => $this->month3,
            'month4' => $this->month4,
            'month5' => $this->month5,
            'month6' => $this->month6,
            'month7' => $this->month7,
            'month8' => $this->month8,
        ]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
