<?php

namespace hospital\models\article;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use hospital\models\user\DoctorParent;

/**
 * DoctorParentSearchModel represents the model behind the search form of `hospital\models\user\DoctorParent`.
 */
class DoctorParentSearchModel extends DoctorParent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'doctorid', 'parentid', 'createtime', 'level'], 'integer'],
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
        $query = DoctorParent::find();

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
            'doctorid' => $this->doctorid,
            'parentid' => $this->parentid,
            'createtime' => $this->createtime,
            'level' => $this->level,
        ]);

        return $dataProvider;
    }
}
