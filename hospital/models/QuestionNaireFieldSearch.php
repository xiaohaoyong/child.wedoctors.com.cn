<?php

namespace hospital\models;

use common\models\QuestionNaireAnswer;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\QuestionNaireField;

/**
 * QuestionNaireFieldSearch represents the model behind the search form about `\common\models\QuestionNaireField`.
 */
class QuestionNaireFieldSearch extends QuestionNaireField
{
    public $birthday_e;
    public $birthday_s;
    public $createtime_e;
    public $createtime_s;
    public $name;
    public $phone;
    public $value;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'qnid', 'userid', 'createtime', 'doctorid', 'state'], 'integer'],
            [['sign'], 'safe'],
            [['birthday_e', 'birthday_s','name'], 'string'],
            [['phone'],'number']
        ];
    }
    public function attributeLabels()
    {
        $return = parent::attributeLabels();
        $return ['name'] = '姓名';
        $return ['phone'] = '手机号';
        $return ['value'] = '内容搜索';

        $return ['createtime_e'] = '填表日期';
        $return ['createtime_s'] = '~';

        $return ['createtime_e'] = '填表日期';
        $return ['createtime_s'] = '~';

        return $return;
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
        $this->load($params);

        $qna = QuestionNaireAnswer::find();
        $qnaids=[];
        if($this->name && $this->qnid==4){
            $qnaids[]=37;
        }
        if($this->name && $this->qnid==1){
            $qnaids[]=1;
        }
        if($this->phone && $this->qnid==4){
            $qnaids[]=39;
        }
        if($this->phone && $this->qnid==1){
            $qnaids[]=2;
        }
//        if($this->birthday_e){
//            $qna->andWhere(['qnaid'=>37]);
//            $qna->andFilterWhere(['>=', 'appoint_date', strtotime($this->appoint_dates)]);
//        }
//        if($this->birthday_s){
//            $qna->andWhere(['qnaid'=>37]);
//            $qna->andFilterWhere(['<=', 'appoint_date', strtotime($this->appoint_dates_end)]);
//        }
        if($this->createtime_e){
            $qna->andFilterWhere(['<=', 'createtime', strtotime($this->createtime_e)]);
        }
        if($this->createtime_s){
            $qna->andFilterWhere(['>=', 'createtime', strtotime($this->createtime_s)]);
        }
        // grid filtering conditions
        $qna->andFilterWhere([
            'doctorid' => \Yii::$app->user->identity->doctorid,
            'qnid'=>$this->qnid,
        ]);
        if($this->name){
            $qna->andFilterWhere(['like', 'answer', $this->name]);
        }
        if($this->phone){
            $qna->andFilterWhere(['like', 'answer', $this->phone]);
        }
        if($qnaids){
            $qna->andWhere(['qnaid'=>$qnaids]);
        }
        $qna->groupBy('qnfid');
        $qna->select('qnfid');

        $data=$qna->column();

        $query = QuestionNaireField::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $data,
        ]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
