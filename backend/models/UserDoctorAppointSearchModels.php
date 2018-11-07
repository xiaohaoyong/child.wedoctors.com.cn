<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserDoctorAppoint;

/**
 * UserDoctorAppointSearchModels represents the model behind the search form about `\common\models\UserDoctorAppoint`.
 */
class UserDoctorAppointSearchModels extends UserDoctorAppoint
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doctorid', 'weeks', 'cycle', 'delay', 'type1_num', 'type2_num', 'type3_num', 'type4_num', 'type5_num', 'type6_num', 'type', 'id'], 'integer'],
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
        $query = UserDoctorAppoint::find();

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
            'doctorid' => $this->doctorid,
            'weeks' => $this->weeks,
            'cycle' => $this->cycle,
            'delay' => $this->delay,
            'type1_num' => $this->type1_num,
            'type2_num' => $this->type2_num,
            'type3_num' => $this->type3_num,
            'type4_num' => $this->type4_num,
            'type5_num' => $this->type5_num,
            'type6_num' => $this->type6_num,
            'type' => $this->type,
            'id' => $this->id,
        ]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
