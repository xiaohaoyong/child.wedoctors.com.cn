<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ChildInfo;

/**
 * ChildSearch represents the model behind the search form about `common\models\ChildInfo`.
 */
class ChildSearch extends ChildInfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'birthday', 'createtime', 'gender', 'source', 'admin', 'doctorid'], 'integer'],
            [['userid','name', 'field54', 'field53', 'field52', 'field51', 'field50', 'field49', 'field48', 'field47', 'field46', 'field45', 'field44', 'field43', 'field42', 'field41', 'field40', 'field39', 'field38', 'field37', 'field27', 'field26', 'field25', 'field24', 'field23', 'field22', 'field21', 'field20', 'field19', 'field18', 'field17', 'field16', 'field15', 'field14', 'field13', 'field7', 'field6', 'field0'], 'safe'],
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
        $query = ChildInfo::find();

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
            'birthday' => $this->birthday,
            'createtime' => $this->createtime,
            'gender' => $this->gender,
            'source' => $this->source,
            'admin' => $this->admin,
            'doctorid' => $this->doctorid,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'field54', $this->field54])
            ->andFilterWhere(['like', 'field53', $this->field53])
            ->andFilterWhere(['like', 'field52', $this->field52])
            ->andFilterWhere(['like', 'field51', $this->field51])
            ->andFilterWhere(['like', 'field50', $this->field50])
            ->andFilterWhere(['like', 'field49', $this->field49])
            ->andFilterWhere(['like', 'field48', $this->field48])
            ->andFilterWhere(['like', 'field47', $this->field47])
            ->andFilterWhere(['like', 'field46', $this->field46])
            ->andFilterWhere(['like', 'field45', $this->field45])
            ->andFilterWhere(['like', 'field44', $this->field44])
            ->andFilterWhere(['like', 'field43', $this->field43])
            ->andFilterWhere(['like', 'field42', $this->field42])
            ->andFilterWhere(['like', 'field41', $this->field41])
            ->andFilterWhere(['like', 'field40', $this->field40])
            ->andFilterWhere(['like', 'field39', $this->field39])
            ->andFilterWhere(['like', 'field38', $this->field38])
            ->andFilterWhere(['like', 'field37', $this->field37])
            ->andFilterWhere(['like', 'field27', $this->field27])
            ->andFilterWhere(['like', 'field26', $this->field26])
            ->andFilterWhere(['like', 'field25', $this->field25])
            ->andFilterWhere(['like', 'field24', $this->field24])
            ->andFilterWhere(['like', 'field23', $this->field23])
            ->andFilterWhere(['like', 'field22', $this->field22])
            ->andFilterWhere(['like', 'field21', $this->field21])
            ->andFilterWhere(['like', 'field20', $this->field20])
            ->andFilterWhere(['like', 'field19', $this->field19])
            ->andFilterWhere(['like', 'field18', $this->field18])
            ->andFilterWhere(['like', 'field17', $this->field17])
            ->andFilterWhere(['like', 'field16', $this->field16])
            ->andFilterWhere(['like', 'field15', $this->field15])
            ->andFilterWhere(['like', 'field14', $this->field14])
            ->andFilterWhere(['like', 'field13', $this->field13])
            ->andFilterWhere(['like', 'field7', $this->field7])
            ->andFilterWhere(['like', 'field6', $this->field6])
            ->andFilterWhere(['like', 'field0', $this->field0]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
