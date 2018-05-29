<?php

namespace hospital\models\user;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * HospitalSearchModel represents the model behind the search form about `app\models\hospital\Hospital`.
 */
class HospitalSearchModel extends Hospital
{
    public $chile_type;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'province', 'city', 'county', 'type', 'rank', 'nature', 'createtime','chile_type'], 'integer'],
            [['name', 'area'], 'safe'],
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
        $return=parent::attributeLabels(); // TODO: Change the autogenerated stub
        $return['chile_type']='儿童年龄';
        return $return;
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
        $query = Hospital::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        Hospital::$chile_type_static=$this->chile_type;
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'province' => 11,
            'city' => 11,
            'county' => $this->county,
            'type' => $this->type,
            'rank' => $this->rank,
            'nature' => $this->nature,
            'createtime' => $this->createtime,
        ]);


        $hosptials=UserDoctor::find()->select('hospitalid')->groupBy('hospitalid')->column();
        $query->andFilterWhere(['in','id',$hosptials]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'area', $this->area]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
