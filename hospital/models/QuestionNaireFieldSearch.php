<?php

namespace hospital\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\QuestionNaireField;

/**
 * QuestionNaireFieldSearch represents the model behind the search form about `\common\models\QuestionNaireField`.
 */
class QuestionNaireFieldSearch extends QuestionNaireField
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'qnid', 'userid', 'createtime', 'doctorid', 'state'], 'integer'],
            [['sign'], 'safe'],
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
        $query = QuestionNaireField::find();

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
            'qnid' => $this->qnid,
            'userid' => $this->userid,
            'createtime' => $this->createtime,
            'doctorid' => \Yii::$app->user->identity->doctorid,
            'state' => $this->state,
        ]);

        $query->andFilterWhere(['like', 'sign', $this->sign]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
