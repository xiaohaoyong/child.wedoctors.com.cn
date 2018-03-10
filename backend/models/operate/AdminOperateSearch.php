<?php
/**
 * 操作日志查询
 */

namespace backend\models\operate;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class AdminOperateSearch extends AdminOperate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id'], 'integer'],
            [['operate_time'],'date', 'format'=>'php:Y-m-d'],
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
        $query = AdminOperate::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'admin_id' => $this->admin_id,
        ]);

        if(!empty($this->operate_time))
        {
            $query->andFilterWhere(['>=','operate_time',strtotime($this->operate_time." 00:00:00")]);
            $query->andFilterWhere(['<=','operate_time',strtotime($this->operate_time." 23:59:59")]);
        }

        return $dataProvider;
    }
}