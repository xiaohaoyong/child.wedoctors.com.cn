<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserDoctor;

/**
 * UserDoctorSearchModel represents the model behind the search form about `common\models\UserDoctor`.
 */
class UserDoctorSearchModel extends UserDoctor
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'sex', 'age', 'birthday', 'phone', 'hospitalid', 'subject_b', 'subject_s', 'title', 'province', 'county', 'city', 'atitle', 'otype','is_guanfang'], 'integer'],
            [['name', 'intro', 'avatar', 'skilful', 'idnum', 'authimg', 'qrcode'], 'safe'],
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
        $query = UserDoctor::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>['pageSize'=>300]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'userid' => $this->userid,
            'sex' => $this->sex,
            'age' => $this->age,
            'birthday' => $this->birthday,
            'phone' => $this->phone,
            'hospitalid' => $this->hospitalid,
            'subject_b' => $this->subject_b,
            'subject_s' => $this->subject_s,
            'title' => $this->title,
            'province' => $this->province,
            'county' => $this->county,
            'city' => $this->city,
            'atitle' => $this->atitle,
            'otype' => $this->otype,
                        'is_guanfang' => $this->is_guanfang,

        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'intro', $this->intro])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'skilful', $this->skilful])
            ->andFilterWhere(['like', 'idnum', $this->idnum])
            ->andFilterWhere(['like', 'authimg', $this->authimg])
            ->andFilterWhere(['like', 'qrcode', $this->qrcode]);

        return $dataProvider;
    }
}
