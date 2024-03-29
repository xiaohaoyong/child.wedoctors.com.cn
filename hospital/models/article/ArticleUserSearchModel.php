<?php

namespace hospital\models\article;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ArticleUserSearchModel represents the model behind the search form about `common\models\ArticleUser`.
 */
class ArticleUserSearchModel extends ArticleUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'childid', 'touserid', 'artid', 'createtime', 'userid', 'level', 'child_type'], 'integer'],
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
        $query = ArticleUser::find();

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
            'childid' => $this->childid,
            'touserid' => $this->touserid,
            'artid' => $this->artid,
            'createtime' => $this->createtime,
            'userid' => $this->userid,
            'level' => $this->level,
            'child_type' => $this->child_type,
        ]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
