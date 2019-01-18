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

    public $field5s;//建册
    public $field5e;//建册
    public $field15s;//核实
    public $field15e;//核实
    public $childbirth_dates;//核实
    public $childbirth_datee;//核实
    public $name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'prenatal_test', 'pt_date', 'prenatal', 'childbirth_date', 'createtime', 'userid', 'pt_value', 'week'], 'integer'],
            [['childbirth_dates','childbirth_datee','field15s','field15e','field5s','field5e','pt_hospital', 'childbirth_hospital','name'], 'safe'],
        ];
    }
    public function attributeLabels(){
        $attr=parent::attributeLabels();
        $attr['name']="孕妇姓名";
        $attr['field15s']="核实后预产期";
        $attr['field15e']="~~";
        $attr['field5s']="建册日期";
        $attr['field5e']="~~";
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

        if($this->field5s){
            $query->leftJoin('pregnancy', '`interview`.`userid` = `pregnancy`.`familyid`');
            $query->andWhere(['>=',Pregnancy::tableName().'.field5',$this->field5s]);
        }
        if($this->field5e){
            $query->leftJoin('pregnancy', '`interview`.`userid` = `pregnancy`.`familyid`');
            $query->andWhere(['<=',Pregnancy::tableName().'.field5',$this->field5e]);
        }
        if($this->field15s){
            $query->leftJoin('pregnancy', '`interview`.`userid` = `pregnancy`.`familyid`');
            $query->andWhere(['>=',Pregnancy::tableName().'.field15',$this->field15s]);
        }
        if($this->field15s){
            $query->leftJoin('pregnancy', '`interview`.`userid` = `pregnancy`.`familyid`');
            $query->andWhere(['<=',Pregnancy::tableName().'.field15',$this->field15e]);
        }
        if($this->childbirth_dates){
            $query->andWhere(['>=',Interview::tableName().'.childbirth_date',$this->field15s]);
        }
        if($this->childbirth_datee){
            $query->andWhere(['<=',Interview::tableName().'.childbirth_date',$this->field15e]);
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
