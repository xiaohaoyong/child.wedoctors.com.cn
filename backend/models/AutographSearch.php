<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Autograph;

/**
 * AutographSearch represents the model behind the search form about `\common\models\Autograph`.
 */
class AutographSearch extends Autograph
{
    public $createtimeS;
    public $createtimeE;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'createtime', 'loginid', 'userid', 'doctorid'], 'integer'],
            [['img'], 'safe'],
            [['createtimeS', 'createtimeE'], 'string'],
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
    public function attributeLabels()
    {
        $attr = parent::attributeLabels();


        $attr['createtimeS'] = '签约日期';
        $attr['createtimeE'] = '~';

        return $attr;
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
            'loginid' => $this->loginid,
            'userid' => $this->userid,
            'doctorid' => $this->doctorid,
        ]);

        if ($this->createtimeS !== '' and $this->createtimeE !== null) {

            $state = strtotime($this->createtimeS . " 00:00:00");
            $end = strtotime($this->createtimeE . " 23:59:59");
            $query->andFilterWhere(['>', '`createtime`', $state]);
            $query->andFilterWhere(['<', '`createtime`', $end]);
        }

        $query->andFilterWhere(['like', 'img', $this->img]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
