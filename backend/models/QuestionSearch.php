<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Question;

/**
 * QuestionSearch represents the model behind the search form about `\common\models\Question`.
 */
class QuestionSearch extends Question
{
    public $startDate;
    public $endDate;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userid', 'createtime', 'childid', 'doctorid', 'orderid', 'level', 'state','is_comment'], 'integer'],
            [['startDate','endDate'], 'date', 'format' => 'php:Y-m-d', 'message'=>'日期格式不对'],
           // ['state','default','value'=>2]

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
        $query = Question::find();

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
        if($this->state == ''){
            $this->state = -1;
        }
        if($this->is_comment == ''){
            $this->is_comment = -1;
        }

    if($this->state == -1){
        $query->andWhere(['in','state',[0,1,2]]);
    }else{
        $query->andWhere(['state'=>$this->state]);
    }
        if($this->is_comment == -1){
            $query->andWhere(['in','is_comment',[0,1]]);
        }else{
            $query->andWhere(['is_comment'=>$this->is_comment]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'userid' => $this->userid,
          //  'createtime' => $this->createtime,
            'childid' => $this->childid,
            'doctorid' => $this->doctorid,
            'orderid' => $this->orderid,
            'level' => $this->level,
           // 'state' => $state,
           // 'is_comment' => $this->is_comment,
        ]);


        if ($this->startDate !== '' and $this->endDate !== null) {

            $state = strtotime($this->startDate . " 00:00:00");
            $end = strtotime($this->endDate . " 23:59:59");
            $query->andFilterWhere(['>', '`createtime`', $state]);
            $query->andFilterWhere(['<', '`createtime`', $end]);
        }
     // echo   $query->createCommand()->getRawSql();
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
    public function attributeLabels()
    {
        $attr = parent::attributeLabels();


        $attr['startDate'] = '创建时间';
        $attr['endDate'] = '~';
       // $attr['county'] = '区/县';

        return $attr;
    }
}
