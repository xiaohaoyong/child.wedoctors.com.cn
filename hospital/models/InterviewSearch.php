<?php

namespace hospital\models;

use common\models\Pregnancy;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Interview;

/**
 * InterviewSearch represents the model behind the search form about `\common\models\Interview`.
 */
class InterviewSearch extends Interview
{
    public $name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'prenatal_test', 'pt_date', 'prenatal', 'childbirth_date', 'createtime', 'userid', 'pt_value', 'week'], 'integer'],
            [['pt_hospital', 'childbirth_hospital','name'], 'safe'],
        ];
    }
    public function attributeLabels(){
        $attr=parent::attributeLabels();
        $attr['name']="孕妇姓名";
        return $attr;
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
        $query = Interview::find();

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

        $query->andWhere(['prenatal_test'=>1]);
        $query->groupBy('userid');

        if ($this->name) {

            $query->leftJoin('pregnancy', '`interview`.`userid` = `pregnancy`.`familyid`');
            $query->andWhere(['pregnancy.field1'=>$this->name]);
        }

        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
