<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Examination;

/**
 * ExaminationSearch represents the model behind the search form about `common\models\Examination`.
 */
class ExaminationSearch extends Examination
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'childid', 'source', 'isupdate'], 'integer'],
            [['field1', 'field2', 'field3', 'field4', 'field5', 'field6', 'field7', 'field8', 'field9', 'field10', 'field11', 'field12', 'field13', 'field14', 'field15', 'field16', 'field17', 'field18', 'field19', 'field20', 'field21', 'field22', 'field23', 'field24', 'field25', 'field26', 'field27', 'field28', 'field29', 'field30', 'field31', 'field32', 'field33', 'field34', 'field35', 'field36', 'field37', 'field38', 'field39', 'field40', 'field41', 'field42', 'field43', 'field44', 'field45', 'field46', 'field47', 'field48', 'field49', 'field50', 'field51', 'field52', 'field53', 'field54', 'field55', 'field56', 'field57', 'field58', 'field59', 'field60', 'field61', 'field62', 'field63', 'field64', 'field65', 'field66', 'field67', 'field68', 'field69', 'field70', 'field71', 'field72', 'field73', 'field74', 'field75', 'field76', 'field77', 'field78', 'field79', 'field80', 'field81', 'field82', 'field83', 'field84', 'field85', 'field86', 'field87', 'field88', 'field89', 'field90', 'field91', 'field92'], 'safe'],
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
        $query = Examination::find();

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
            'childid' => $this->childid,
            'source' => $this->source,
            'isupdate' => $this->isupdate,
        ]);

        $query->andFilterWhere(['like', 'field1', $this->field1])
            ->andFilterWhere(['like', 'field2', $this->field2])
            ->andFilterWhere(['like', 'field3', $this->field3])
            ->andFilterWhere(['like', 'field4', $this->field4])
            ->andFilterWhere(['like', 'field5', $this->field5])
            ->andFilterWhere(['like', 'field6', $this->field6])
            ->andFilterWhere(['like', 'field7', $this->field7])
            ->andFilterWhere(['like', 'field8', $this->field8])
            ->andFilterWhere(['like', 'field9', $this->field9])
            ->andFilterWhere(['like', 'field10', $this->field10])
            ->andFilterWhere(['like', 'field11', $this->field11])
            ->andFilterWhere(['like', 'field12', $this->field12])
            ->andFilterWhere(['like', 'field13', $this->field13])
            ->andFilterWhere(['like', 'field14', $this->field14])
            ->andFilterWhere(['like', 'field15', $this->field15])
            ->andFilterWhere(['like', 'field16', $this->field16])
            ->andFilterWhere(['like', 'field17', $this->field17])
            ->andFilterWhere(['like', 'field18', $this->field18])
            ->andFilterWhere(['like', 'field19', $this->field19])
            ->andFilterWhere(['like', 'field20', $this->field20])
            ->andFilterWhere(['like', 'field21', $this->field21])
            ->andFilterWhere(['like', 'field22', $this->field22])
            ->andFilterWhere(['like', 'field23', $this->field23])
            ->andFilterWhere(['like', 'field24', $this->field24])
            ->andFilterWhere(['like', 'field25', $this->field25])
            ->andFilterWhere(['like', 'field26', $this->field26])
            ->andFilterWhere(['like', 'field27', $this->field27])
            ->andFilterWhere(['like', 'field28', $this->field28])
            ->andFilterWhere(['like', 'field29', $this->field29])
            ->andFilterWhere(['like', 'field30', $this->field30])
            ->andFilterWhere(['like', 'field31', $this->field31])
            ->andFilterWhere(['like', 'field32', $this->field32])
            ->andFilterWhere(['like', 'field33', $this->field33])
            ->andFilterWhere(['like', 'field34', $this->field34])
            ->andFilterWhere(['like', 'field35', $this->field35])
            ->andFilterWhere(['like', 'field36', $this->field36])
            ->andFilterWhere(['like', 'field37', $this->field37])
            ->andFilterWhere(['like', 'field38', $this->field38])
            ->andFilterWhere(['like', 'field39', $this->field39])
            ->andFilterWhere(['like', 'field40', $this->field40])
            ->andFilterWhere(['like', 'field41', $this->field41])
            ->andFilterWhere(['like', 'field42', $this->field42])
            ->andFilterWhere(['like', 'field43', $this->field43])
            ->andFilterWhere(['like', 'field44', $this->field44])
            ->andFilterWhere(['like', 'field45', $this->field45])
            ->andFilterWhere(['like', 'field46', $this->field46])
            ->andFilterWhere(['like', 'field47', $this->field47])
            ->andFilterWhere(['like', 'field48', $this->field48])
            ->andFilterWhere(['like', 'field49', $this->field49])
            ->andFilterWhere(['like', 'field50', $this->field50])
            ->andFilterWhere(['like', 'field51', $this->field51])
            ->andFilterWhere(['like', 'field52', $this->field52])
            ->andFilterWhere(['like', 'field53', $this->field53])
            ->andFilterWhere(['like', 'field54', $this->field54])
            ->andFilterWhere(['like', 'field55', $this->field55])
            ->andFilterWhere(['like', 'field56', $this->field56])
            ->andFilterWhere(['like', 'field57', $this->field57])
            ->andFilterWhere(['like', 'field58', $this->field58])
            ->andFilterWhere(['like', 'field59', $this->field59])
            ->andFilterWhere(['like', 'field60', $this->field60])
            ->andFilterWhere(['like', 'field61', $this->field61])
            ->andFilterWhere(['like', 'field62', $this->field62])
            ->andFilterWhere(['like', 'field63', $this->field63])
            ->andFilterWhere(['like', 'field64', $this->field64])
            ->andFilterWhere(['like', 'field65', $this->field65])
            ->andFilterWhere(['like', 'field66', $this->field66])
            ->andFilterWhere(['like', 'field67', $this->field67])
            ->andFilterWhere(['like', 'field68', $this->field68])
            ->andFilterWhere(['like', 'field69', $this->field69])
            ->andFilterWhere(['like', 'field70', $this->field70])
            ->andFilterWhere(['like', 'field71', $this->field71])
            ->andFilterWhere(['like', 'field72', $this->field72])
            ->andFilterWhere(['like', 'field73', $this->field73])
            ->andFilterWhere(['like', 'field74', $this->field74])
            ->andFilterWhere(['like', 'field75', $this->field75])
            ->andFilterWhere(['like', 'field76', $this->field76])
            ->andFilterWhere(['like', 'field77', $this->field77])
            ->andFilterWhere(['like', 'field78', $this->field78])
            ->andFilterWhere(['like', 'field79', $this->field79])
            ->andFilterWhere(['like', 'field80', $this->field80])
            ->andFilterWhere(['like', 'field81', $this->field81])
            ->andFilterWhere(['like', 'field82', $this->field82])
            ->andFilterWhere(['like', 'field83', $this->field83])
            ->andFilterWhere(['like', 'field84', $this->field84])
            ->andFilterWhere(['like', 'field85', $this->field85])
            ->andFilterWhere(['like', 'field86', $this->field86])
            ->andFilterWhere(['like', 'field87', $this->field87])
            ->andFilterWhere(['like', 'field88', $this->field88])
            ->andFilterWhere(['like', 'field89', $this->field89])
            ->andFilterWhere(['like', 'field90', $this->field90])
            ->andFilterWhere(['like', 'field91', $this->field91])
            ->andFilterWhere(['like', 'field92', $this->field92]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
