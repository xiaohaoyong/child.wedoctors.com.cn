<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\HospitalMailShow;

/**
 * HospitalMailShowSearch represents the model behind the search form about `\common\models\HospitalMailShow`.
 */
class HospitalMailShowSearch extends HospitalMailShow
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'mailid', 'hospitalid', 'createtime'], 'integer'],
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
        $query = HospitalMailShow::find();

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
            'mailid' => $this->mailid,
            'hospitalid' => $this->hospitalid,
            'createtime' => $this->createtime,
        ]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
