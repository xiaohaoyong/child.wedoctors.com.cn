<?php

namespace hospital\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Pregnancy;

/**
 * PregnancySearch represents the model behind the search form about `\common\models\Pregnancy`.
 */
class PregnancySearch extends Pregnancy
{
    public $level;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level','id', 'familyid', 'field3', 'field5', 'field7', 'field8', 'field9', 'field12', 'field13', 'field14', 'field15', 'field17', 'field18', 'field19', 'field20', 'field21', 'field22', 'field23', 'field24', 'field28', 'field29', 'field30', 'field31', 'field32', 'field35', 'field39', 'field40', 'field41', 'field43', 'field45', 'field46', 'field47', 'field48', 'field49', 'field50', 'field51', 'field52', 'field53', 'field54', 'field55', 'field56', 'field57', 'field58', 'field60', 'field61', 'field62', 'field63', 'field64', 'field65', 'field67', 'field68', 'field70', 'field72', 'field74', 'field76', 'field77', 'field78', 'field80', 'field81', 'field83', 'field84', 'field85', 'field87', 'field88', 'source', 'isupdate', 'createtime'], 'integer'],
            [['field0', 'field1','field2','field11','field12', 'field4', 'field6', 'field10', 'field25', 'field26', 'field27', 'field33', 'field34', 'field36', 'field37', 'field38', 'field42', 'field44', 'field59', 'field66', 'field75', 'field79', 'field82', 'field86'], 'safe'],
            [['field71', 'field73'], 'number'],
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

    public function attributeLabels(){
        $attr=parent::attributeLabels();
        $attr['level']='是否签约';
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
        $hospitalid = \Yii::$app->user->identity->hospital;
        $doctorid = \common\models\UserDoctor::findOne(['hospitalid' => \Yii::$app->user->identity->hospital]);
        $query = Pregnancy::find();

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
        $query->andWhere(['field49'=>0]);
        $query->andWhere(['pregnancy.doctorid'=>$hospitalid]);
        if($this->field11){

            $query->andWhere(['or',['field11'=>strtotime($this->field11)],['field16'=>strtotime($this->field11)]]);//操作符格式的嵌套

        }
        if($this->level){
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `pregnancy`.`familyid`');
            $query->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid->userid]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'familyid' => $this->familyid,
            'field2' => $this->field2,
            'field3' => $this->field3,
            'field5' => $this->field5,
            'field7' => $this->field7,
            'field8' => $this->field8,
            'field9' => $this->field9,
            'field12' => $this->field12,
            'field13' => $this->field13,
            'field14' => $this->field14,
            'field15' => $this->field15,
            'field17' => $this->field17,
            'field18' => $this->field18,
            'field19' => $this->field19,
            'field20' => $this->field20,
            'field21' => $this->field21,
            'field22' => $this->field22,
            'field23' => $this->field23,
            'field24' => $this->field24,
            'field28' => $this->field28,
            'field29' => $this->field29,
            'field30' => $this->field30,
            'field31' => $this->field31,
            'field32' => $this->field32,
            'field35' => $this->field35,
            'field39' => $this->field39,
            'field40' => $this->field40,
            'field41' => $this->field41,
            'field43' => $this->field43,
            'field45' => $this->field45,
            'field46' => $this->field46,
            'field47' => $this->field47,
            'field48' => $this->field48,
            'field50' => $this->field50,
            'field51' => $this->field51,
            'field52' => $this->field52,
            'field53' => $this->field53,
            'field54' => $this->field54,
            'field55' => $this->field55,
            'field56' => $this->field56,
            'field57' => $this->field57,
            'field58' => $this->field58,
            'field60' => $this->field60,
            'field61' => $this->field61,
            'field62' => $this->field62,
            'field63' => $this->field63,
            'field64' => $this->field64,
            'field65' => $this->field65,
            'field67' => $this->field67,
            'field68' => $this->field68,
            'field70' => $this->field70,
            'field71' => $this->field71,
            'field72' => $this->field72,
            'field73' => $this->field73,
            'field74' => $this->field74,
            'field76' => $this->field76,
            'field77' => $this->field77,
            'field78' => $this->field78,
            'field80' => $this->field80,
            'field81' => $this->field81,
            'field83' => $this->field83,
            'field84' => $this->field84,
            'field85' => $this->field85,
            'field87' => $this->field87,
            'field88' => $this->field88,
            'source' => $this->source,
            'isupdate' => $this->isupdate,
            'createtime' => $this->createtime,
        ]);

        $query->andFilterWhere(['like', 'field0', $this->field0])
            ->andFilterWhere(['like', 'field1', $this->field1])
            ->andFilterWhere(['like', 'field4', $this->field4])
            ->andFilterWhere(['like', 'field6', $this->field6])
            ->andFilterWhere(['like', 'field10', $this->field10])
            ->andFilterWhere(['like', 'field25', $this->field25])
            ->andFilterWhere(['like', 'field26', $this->field26])
            ->andFilterWhere(['like', 'field27', $this->field27])
            ->andFilterWhere(['like', 'field33', $this->field33])
            ->andFilterWhere(['like', 'field34', $this->field34])
            ->andFilterWhere(['like', 'field36', $this->field36])
            ->andFilterWhere(['like', 'field37', $this->field37])
            ->andFilterWhere(['like', 'field38', $this->field38])
            ->andFilterWhere(['like', 'field42', $this->field42])
            ->andFilterWhere(['like', 'field44', $this->field44])
            ->andFilterWhere(['like', 'field59', $this->field59])
            ->andFilterWhere(['like', 'field66', $this->field66])
            ->andFilterWhere(['like', 'field75', $this->field75])
            ->andFilterWhere(['like', 'field79', $this->field79])
            ->andFilterWhere(['like', 'field82', $this->field82])
            ->andFilterWhere(['like', 'field86', $this->field86]);
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);
        //var_dump($query->createCommand()->getRawSql());exit;
        return $dataProvider;
    }
}
