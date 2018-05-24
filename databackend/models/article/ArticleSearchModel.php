<?php

namespace databackend\models\article;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ArticleSearchModel represents the model behind the search form about `app\models\article\Article`.
 */
class ArticleSearchModel extends Article
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'catid', 'subject', 'subject_pid', 'level', 'createtime', 'child_type', 'num', 'type'], 'integer'],
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
        $query = Article::find();

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
            'catid' => $this->catid,
            'level' => $this->level,
            'createtime' => $this->createtime,
            'child_type' => $this->child_type,
            'subject' => $this->subject,
            'subject_pid' => $this->subject_pid,
            'num' => $this->num,
            'type' => $this->type,
        ]);
        if (\Yii::$app->user->identity->type != 1) {
            $query->andFilterWhere(['datauserid' => \Yii::$app->user->identity->hospital]);
        }
        if (\Yii::$app->user->identity->hospital != 110564 || \Yii::$app->user->identity->hospital != 110559){
            $query->andFilterWhere(['not in', 'datauserid', [110564, 110559]]);
    }
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);
        return $dataProvider;
    }
}
