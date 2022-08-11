<?php

namespace hospital\models;

use hospital\models\user\DoctorParent;
use hospital\models\user\UserDoctor;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Autograph;

/**
 * AutographSearch represents the model behind the search form about `\common\models\Autograph`.
 */
class AutographSearch extends Autograph
{
    public $father;
    public $mother;
    public $childname;
    public $t;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['t','id', 'createtime', 'loginid', 'userid'], 'integer'],
            [['father', 'mother', 'childname'], 'string'],

            [['img','createtimes','createtimese'], 'safe'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'createtime' => '签约时间',
            'img' => '签名',
            'userid' => '用户',
            'doctorid'=>'社区医院ID',
            'father'=>'父亲姓名',
            'mother'=>'母亲姓名',
            'childname'=>'儿童姓名',
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

        $t = $this->t;

        $userDoctor = UserDoctor::findOne(['hospitalid' => Yii::$app->user->identity->hospitalid]);
        $dp = \common\models\DoctorParent::find()->select('doctor_parent.parentid')
            ->andFilterWhere(['doctor_parent.doctorid' => $userDoctor->userid]);


        if ($t) {
            $dp->leftJoin('pregnancy', '`pregnancy`.`familyid` = `doctor_parent`.`parentid`');
            $dp->andWhere(['>', 'pregnancy.familyid', 0]);
            $dp->andWhere(['pregnancy.field49'=>0]);
        }else{
            $dp->leftJoin('child_info', '`child_info`.`userid` = `doctor_parent`.`parentid`');
            $dp->andWhere(['>', 'child_info.doctorid', 0]);
        }

        if($this->father || $this->mother){
            $dp->leftJoin('user_parent', '`user_parent`.`userid` = `doctor_parent`.`parentid`');
            if($this->father) {
                $dp->andWhere(['user_parent.father' => $this->father]);
            }
            if($this->mother) {
                $dp->andWhere(['user_parent.mother' => $this->mother]);
            }
        }
        if($this->createtimes){
            $dp->andWhere(['>','autograph.createtime',strtotime($this->createtimes)]);
        }
        if($this->createtimese){
            $dp->andWhere(['<','autograph.createtime',strtotime($this->createtimese)]);
        }

        if($this->childname){
            if($t) {
                $dp->leftJoin('child_info', '`child_info`.`userid` = `doctor_parent`.`parentid`');
            }
            $dp->andWhere(['child_info.name'=>$this->childname]);
        }

        $doctorParent = $dp->column();
        if (!$doctorParent) {
            $doctorParent = [0];
        }

        $query->andWhere(['in', 'userid', $doctorParent]);
        $query->orderBy([self::primaryKey()[0] => SORT_DESC]);
        return $dataProvider;
    }
}
