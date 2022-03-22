<?php

namespace databackend\models\user;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserDoctorSearchModel represents the model behind the search form about `app\models\user\UserDoctor`.
 */
class UserDoctorSearchModel extends UserDoctor
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'sex', 'age', 'birthday', 'phone', 'hospitalid', 'subject_b', 'subject_s', 'title', 'province', 'county', 'city', 'atitle', 'otype'], 'integer'],
            [['name', 'intro', 'avatar', 'skilful', 'idnum', 'authimg'], 'safe'],
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
            'is_guanfang'=>0,
        ]);

        if(\Yii::$app->user->identity->type != 1)
        {
            $query->andFilterWhere(['hospitalid'=>\Yii::$app->user->identity->hospital]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'intro', $this->intro])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'skilful', $this->skilful])
            ->andFilterWhere(['like', 'idnum', $this->idnum])
            ->andFilterWhere(['like', 'authimg', $this->authimg]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);
        $query->andFilterWhere(['>','userid','37'])->andFilterWhere(['!=','county','1114']);
        return $dataProvider;
    }
}
