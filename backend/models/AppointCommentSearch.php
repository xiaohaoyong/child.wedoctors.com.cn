<?php

namespace backend\models;

use common\models\AppointComment;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class AppointCommentSearch
 * @package backend\models
 */
class AppointCommentSearch extends AppointComment
{

    public $startDate;
    public $endDate;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','userid','aid','is_envir','is_process','is_staff'], 'integer'],
            [['createtime'], 'date', 'format' => 'php:Y-m-d', 'message'=>'日期格式不对']
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
        $query = AppointComment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if($this->aid){
            $query->andWhere(['aid'=>$this->aid]);
        }
        if($this->userid){
            $query->andWhere(['userid'=>$this->userid]);
        }
        if($this->doctorid){
            $query->andWhere(['doctorid'=>$this->doctorid]);
        }
        //满意度
        if($this->is_envir){
            $query->andWhere(['is_envir'=>$this->is_envir]);
        }
        if($this->is_process){
            $query->andWhere(['is_process'=>$this->is_process]);
        }
        if($this->is_staff){
            $query->andWhere(['is_staff'=>$this->is_staff]);
        }
        if($this->createtime){
            $query->andFilterWhere(['>=', 'createtime', strtotime($this->createtime)]);
            $ends=strtotime($this->createtime)+86400;
            $query->andFilterWhere(['<=', 'createtime', $ends]);
        }
        return $dataProvider;
    }
}
