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
    public $county;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','userid','aid','is_envir','is_process','is_staff','county','doctorid','is_rate'], 'integer'],
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
            $query->andFilterWhere(['aid'=>$this->aid]);
        }
        if($this->userid){
            $query->andFilterWhere(['userid'=>$this->userid]);
        }
        if($this->doctorid){
            $query->andFilterWhere(['doctorid'=>$this->doctorid]);
        }
        //满意度
        if($this->is_envir){
            $query->andFilterWhere(['is_envir'=>$this->is_envir]);
        }
        if($this->is_process){
            $query->andFilterWhere(['is_process'=>$this->is_process]);
        }
        if($this->is_staff){
            $query->andFilterWhere(['is_staff'=>$this->is_staff]);
        }
        if($this->startDate){
            $query->andFilterWhere(['>=', 'createtime', strtotime($this->startDate)]);
        }
        if($this->endDate){
            $ends=strtotime($this->endDate." 23:59:59");
            $query->andFilterWhere(['<=', 'createtime', $ends]);
        }
        if($this->is_rate){
            $query->andFilterWhere(['is_rate'=>$this->is_rate]);
        }
       
		$query->orderBy([self::primaryKey()[0]=>SORT_DESC]);
        return $dataProvider;
    }
}
