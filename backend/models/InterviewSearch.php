<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Interview;

/**
 * InterviewSearch represents the model behind the search form about `\common\models\Interview`.
 */
class InterviewSearch extends Interview
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'prenatal_test', 'pt_date', 'prenatal', 'childbirth_date', 'createtime', 'userid', 'pt_value', 'week', 'sex', 'childbirth_type'], 'integer'],
            [['pt_hospital', 'childbirth_hospital'], 'safe'],
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
        $query = Interview::find();

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
            'prenatal_test' => $this->prenatal_test,
            'pt_date' => $this->pt_date,
            'prenatal' => $this->prenatal,
            'childbirth_date' => $this->childbirth_date,
            'createtime' => $this->createtime,
            'userid' => $this->userid,
            'pt_value' => $this->pt_value,
            'week' => $this->week,
            'sex' => $this->sex,
            'childbirth_type' => $this->childbirth_type,
        ]);

        $query->andFilterWhere(['like', 'pt_hospital', $this->pt_hospital])
            ->andFilterWhere(['like', 'childbirth_hospital', $this->childbirth_hospital]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
