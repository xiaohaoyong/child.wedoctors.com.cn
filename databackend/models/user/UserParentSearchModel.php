<?php

namespace databackend\models\user;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserParentSearchModel represents the model behind the search form of `databackend\models\user\UserParent`.
 */
class UserParentSearchModel extends UserParent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'mother_phone', 'father_phone', 'father_birthday', 'state', 'source'], 'integer'],
            [['mother', 'mother_id', 'father', 'address', 'field34', 'field33', 'field30', 'field29', 'field28', 'field12', 'field11', 'field1'], 'safe'],
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
        $query = UserParent::find();

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

        if($this->field11)
        {
            $query->orFilterWhere(['mother'=>$this->field11]);
            $query->orFilterWhere(['father'=>$this->field11]);
            $query->orFilterWhere(['field11'=>$this->field11]);
        }
        if($this->field12)
        {
            $query->orFilterWhere(['mother_phone'=>$this->field12]);
            $query->orFilterWhere(['father_phone'=>$this->field12]);
            $query->orFilterWhere(['field12'=>$this->field12]);
        }
        return $dataProvider;
    }
}
