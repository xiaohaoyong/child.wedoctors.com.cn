<?php

namespace backend\models;

use common\models\QuestionComment;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * QuestionSearch represents the model behind the search form about `\common\models\Question`.
 */
class QuestionCommentSearch extends QuestionComment
{

    public $startDate;
    public $endDate;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','userid','qid','createtime','is_satisfied','is_solve'], 'integer'],
            [['startDate','endDate'], 'date', 'format' => 'php:Y-m-d', 'message'=>'日期格式不对']
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
        $query = QuestionComment::find();

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

        if($this->qid){
            $query->andWhere(['qid'=>$this->qid]);
        }
        if($this->userid){
            $query->andWhere(['userid'=>$this->userid]);
        }
        //满意度
        if($this->is_satisfied){
            $query->andWhere(['is_satisfied'=>$this->is_satisfied]);
        }
        //问题是否解决
        if($this->is_solve){
            $query->andWhere(['is_solve'=>$this->is_solve]);
        }

        if($this->startDate){
            $query->andFilterWhere(['>=', 'createtime', strtotime($this->startDate)]);
        }
        if($this->endDate){
            $query->andFilterWhere(['<=', 'createtime', strtotime($this->endDate)]);
        }
        // grid filtering conditions
//echo $query->createCommand()->getRawSql();die;
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
