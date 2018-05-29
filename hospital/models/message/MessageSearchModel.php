<?php

namespace hospital\models\message;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MessageSearchModel represents the model behind the search form about `app\models\message\Message`.
 */
class MessageSearchModel extends Message
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'userid', 'usertype', 'createtime'], 'integer'],
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
        $query = Message::find();

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
            'type' => $this->type,
            'userid' => $this->userid,
            'usertype' => $this->usertype,
            'createtime' => $this->createtime,
        ]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
