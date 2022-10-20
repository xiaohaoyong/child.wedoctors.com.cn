<?php

namespace hospital\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\QuestionNaireAnswer;

/**
 * QuestionNaireAnswerSearch represents the model behind the search form about `\common\models\QuestionNaireAnswer`.
 */
class QuestionNaireAnswerSearch extends QuestionNaireAnswer
{
    public $birthday_e;
    public $birthday_s;
    public $createtime_e;
    public $createtime_s;
    public $name;
    public $phone;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'qnaid', 'userid', 'createtime', 'qnid', 'doctorid', 'qnfid', 'isv'], 'integer'],
            [['answer'], 'safe'],
            [['birthday_e', 'birthday_s','name'], 'string'],
            [['phone'],'number']

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
        $query = QuestionNaireAnswer::find();

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
            'doctorid' => \Yii::$app->user->identity->doctorid,
            'qnid'=>$this->qnid,
        ]);
        $query->andFilterWhere(['like', 'answer', $this->answer]);
        $query->groupBy('qnid');
        $query->select('qnfid');
        $data=$query->column();

        $qna=QuestionNaireAnswer::find()->where(['qnfid'=>$data]);



        return $data;
    }
}
