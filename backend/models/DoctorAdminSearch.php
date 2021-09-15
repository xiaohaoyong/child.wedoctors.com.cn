<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DoctorAdmin;

/**
 * DoctorAdminSearch represents the model behind the search form about `\common\models\DoctorAdmin`.
 */
class DoctorAdminSearch extends DoctorAdmin
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'phone', 'hospitalid', 'createtime', 'logintime'], 'integer'],
            [['auth_key', 'accessToken'], 'safe'],
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
        $query = DoctorAdmin::find();

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
            'phone' => $this->phone,
            'hospitalid' => $this->hospitalid,
            'createtime' => $this->createtime,
            'logintime' => $this->logintime,
        ]);

        $query->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'accessToken', $this->accessToken]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
