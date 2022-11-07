<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SigningRecord;

/**
 * SigningRecordSearch represents the model behind the search form about `common\models\SigningRecord`.
 */
class SigningRecordSearch extends SigningRecord
{

    public $startDate;
    public $endDate;
    public $county;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userid', 'type', 'sign_item_id_from', 'sign_item_id_to', 'status', 'createtime'], 'integer'],
            [['info_pics', 'remark'], 'safe'],
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
        $query = SigningRecord::find();

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
            'userid' => $this->userid,
            'type' => $this->type,
            'sign_item_id_from' => $this->sign_item_id_from,
            'sign_item_id_to' => $this->sign_item_id_to,
            'status' => $this->status,
            'operator' => $this->operator,
        ]);


        var_dump($this->startDate);
        var_dump($this->endDate);

        if($this->startDate){
            var_dump('123');
            $query->andFilterWhere(['>=', 'createtime', strtotime($this->startDate)]);
        }
        if($this->endDate){
            var_dump('456');

            $ends=strtotime($this->endDate)+86400;
            $query->andFilterWhere(['<=', 'createtime', $ends]);
        }

        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
