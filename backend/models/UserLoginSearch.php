<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserLogin;

/**
 * UserLoginSearch represents the model behind the search form about `common\models\UserLogin`.
 */
class UserLoginSearch extends UserLogin
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'logintime', 'phone', 'id', 'createtime'], 'integer'],
            [['password', 'openid', 'xopenid', 'unionid', 'hxusername','dopenid'], 'safe'],
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
        $query = UserLogin::find();

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
            'logintime' => $this->logintime,
            'phone' => $this->phone,
            'id' => $this->id,
            'createtime' => $this->createtime,
        ]);

        $query->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'xopenid', $this->xopenid])
            ->andFilterWhere(['like', 'unionid', $this->unionid])
            ->andFilterWhere(['like', 'dopenid', $this->dopenid])
            ->andFilterWhere(['like', 'hxusername', $this->hxusername]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
