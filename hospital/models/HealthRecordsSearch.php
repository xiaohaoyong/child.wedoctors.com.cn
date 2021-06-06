<?php

namespace hospital\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\HealthRecords;

/**
 * HealthRecordsSearch represents the model behind the search form about `\common\models\HealthRecords`.
 */
class HealthRecordsSearch extends HealthRecords
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'field1', 'field2', 'field4', 'field5', 'field7', 'field8', 'field42', 'field16', 'field17', 'field18', 'field19', 'field20', 'field21', 'field22', 'field23', 'field24', 'field25', 'field26', 'field27', 'field28', 'field30', 'field40', 'field39', 'field35', 'field36', 'field37', 'field38', 'createtime', 'doctorid', 'userid', 'field43', 'field44'], 'integer'],
            [['field3', 'field5_text', 'field6', 'field8_text', 'field15', 'field41', 'field16_text', 'field17_text', 'field18_text', 'field19_text', 'field20_text', 'field29', 'field34', 'field31', 'field32', 'field33'], 'safe'],
            [['field9', 'field10', 'field11', 'field12', 'field13', 'field14'], 'number'],
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
        $query = HealthRecords::find();

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
            'field1' => $this->field1,
            'field2' => $this->field2,
            'field4' => $this->field4,
            'field5' => $this->field5,
            'field7' => $this->field7,
            'field8' => $this->field8,
            'field9' => $this->field9,
            'field10' => $this->field10,
            'field11' => $this->field11,
            'field12' => $this->field12,
            'field13' => $this->field13,
            'field14' => $this->field14,
            'field42' => $this->field42,
            'field16' => $this->field16,
            'field17' => $this->field17,
            'field18' => $this->field18,
            'field19' => $this->field19,
            'field20' => $this->field20,
            'field21' => $this->field21,
            'field22' => $this->field22,
            'field23' => $this->field23,
            'field24' => $this->field24,
            'field25' => $this->field25,
            'field26' => $this->field26,
            'field27' => $this->field27,
            'field28' => $this->field28,
            'field30' => $this->field30,
            'field40' => $this->field40,
            'field39' => $this->field39,
            'field35' => $this->field35,
            'field36' => $this->field36,
            'field37' => $this->field37,
            'field38' => $this->field38,
            'createtime' => $this->createtime,
            'doctorid' => $this->doctorid,
            'userid' => $this->userid,
            'field43' => $this->field43,
            'field44' => $this->field44,
        ]);

        $query->andFilterWhere(['like', 'field3', $this->field3])
            ->andFilterWhere(['like', 'field5_text', $this->field5_text])
            ->andFilterWhere(['like', 'field6', $this->field6])
            ->andFilterWhere(['like', 'field8_text', $this->field8_text])
            ->andFilterWhere(['like', 'field15', $this->field15])
            ->andFilterWhere(['like', 'field41', $this->field41])
            ->andFilterWhere(['like', 'field16_text', $this->field16_text])
            ->andFilterWhere(['like', 'field17_text', $this->field17_text])
            ->andFilterWhere(['like', 'field18_text', $this->field18_text])
            ->andFilterWhere(['like', 'field19_text', $this->field19_text])
            ->andFilterWhere(['like', 'field20_text', $this->field20_text])
            ->andFilterWhere(['like', 'field29', $this->field29])
            ->andFilterWhere(['like', 'field34', $this->field34])
            ->andFilterWhere(['like', 'field31', $this->field31])
            ->andFilterWhere(['like', 'field32', $this->field32])
            ->andFilterWhere(['like', 'field33', $this->field33]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);
        //echo $query->createCommand()->getRawSql();exit;
        return $dataProvider;
    }
}
