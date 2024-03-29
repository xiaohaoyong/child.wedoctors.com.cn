<?php

namespace databackend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Autograph;

/**
 * AutographSearchModels represents the model behind the search form about `\common\models\Autograph`.
 */
class AutographSearchModels extends Autograph
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'createtime', 'loginid', 'userid', 'doctorid'], 'integer'],
            [['img'], 'safe'],
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
        $query = Autograph::find();

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
            'createtime' => $this->createtime,
            'loginid' => $this->loginid,
            'userid' => $this->userid,
            'doctorid' => $this->doctorid,
        ]);

        $query->andFilterWhere(['like', 'img', $this->img]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
