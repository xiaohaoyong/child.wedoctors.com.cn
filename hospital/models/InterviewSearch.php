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
    public $field90;//建册

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
            [['field90', 'childbirth_dates', 'childbirth_datee', 'field15s', 'field15e', 'field5s', 'field5e', 'pt_hospital', 'childbirth_hospital', 'name'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        $attr = parent::attributeLabels();
        $attr['name'] = "孕妇姓名";
        $attr['field15s'] = "核实后预产期";
        $attr['field15e'] = "~~";
        $attr['field5s'] = "建册日期";
        $attr['field5e'] = "~~";
        $attr['childbirth_dates'] = "分娩日期";
        $attr['childbirth_datee'] = "~~";
        $attr['field90'] = '户籍地';
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
        $hospitalid = \Yii::$app->user->identity->hospital;
        $doctorid = \common\models\UserDoctor::findOne(['hospitalid' => $hospitalid]);
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
        $query->leftJoin('pregnancy', '`interview`.`userid` = `pregnancy`.`familyid`');

        if ($this->field5s) {
            $query->andWhere(['>=', Pregnancy::tableName() . '.field5', strtotime($this->field5s)]);
        }
        if ($this->field5e) {
            $query->andWhere(['<=', Pregnancy::tableName() . '.field5', strtotime($this->field5e)]);
        }
        if ($this->field15s) {
            $query->andWhere(['>=', Pregnancy::tableName() . '.field15', strtotime($this->field15s)]);
        }
        if ($this->field15s) {
            $query->andWhere(['<=', Pregnancy::tableName() . '.field15', strtotime($this->field15e)]);
        }
        if ($this->childbirth_dates) {
            $query->andWhere(['>=', Interview::tableName() . '.childbirth_date', strtotime($this->field15s)]);
        }
        if ($this->childbirth_datee) {
            $query->andWhere(['<=', Interview::tableName() . '.childbirth_date', strtotime($this->field15e)]);
        }
        if ($this->field90) {
            $query->andWhere(['in', Pregnancy::tableName() . '.field90', $this->field90]);
        }

        $query->andWhere(['pregnancy.doctorid' => $hospitalid]);
        $query->andWhere([Interview::tableName() . '.prenatal_test' => 1]);
        $query->groupBy(Interview::tableName() . '.userid');

        if ($this->name) {

            $query->andWhere(['pregnancy.field1' => $this->name]);
        }

        $query->orderBy([self::primaryKey()[0] => SORT_DESC]);
        //echo $query->createCommand()->getRawSql();
        return $dataProvider;
    }
}
