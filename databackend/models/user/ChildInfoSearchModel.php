<?php

namespace databackend\models\user;

use databackend\models\behaviors\UserParent;
use databackend\models\user\ChildInfo;
use common\models\User;
use databackend\controllers\ChildInfoController;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ChildInfoSearchModel represents the model behind the search form about `app\models\user\ChildInfo`.
 */
class ChildInfoSearchModel extends ChildInfo
{
    public $level;
    public $docpartime;
    public $username;
    public $userphone;

    public function behaviors()
    {
        return [
            \databackend\models\behaviors\DoctorParent::className(),
            \databackend\models\behaviors\UserParent::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userid', 'birthday', 'createtime', 'level','admin'], 'integer'],
            [['docpartime', 'username'], 'string'],
            [['userphone'], 'integer'],

            [['name'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'level' => '签约',
            'docpartime' => '签约时间',
            'username' => '母亲姓名',
            'userphone' => '母亲手机号'
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
        $query = \common\models\ChildInfo::find();

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
        //签约条件
        //$doctorParent = $this->doctorParent->load($params);
        if (isset($this->level) || isset($this->docpartime)) {
            //var_dump($params);
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');

            if ($this->level) {
                $query->andFilterWhere(['`doctor_parent`.`level`' => $this->level]);

                $doctorid=UserDoctor::findOne(['hospitalid'=>\Yii::$app->user->identity->hospital])->userid;
                $query->andFilterWhere(['`doctor_parent`.`doctorid`'=>$doctorid]);
            }
            if ($this->docpartime) {
                $state = strtotime($this->docpartime . " 00:00:00");
                $end = strtotime($this->docpartime . " 23:59:59");
                $query->andFilterWhere(['>', '`doctor_parent`.`createtime`', $state]);
                $query->andFilterWhere(['<', '`doctor_parent`.`createtime`', $end]);
            }
        }
//        'username' => '联系人姓名',
//            'userphone' => '联系人电话'
        if ($this->username || $this->userphone) {
            $query->leftJoin('user_parent', '`user_parent`.`userid` = `child_info`.`userid`');

            if ($this->username) {
                $query->andFilterWhere(['`user_parent`.`mother`' => $this->username]);
            }
            if ($this->userphone) {
                $query->andFilterWhere(['`user_parent`.`mother_phone`' => $this->userphone]);

            }
        }
        if(\Yii::$app->user->identity->hospital && !$this->level && !$this->docpartime) {
            $query->andFilterWhere(['source' => \Yii::$app->user->identity->hospital]);
        }

        $query->orderBy([self::primaryKey()[0] => SORT_DESC]);

        //var_dump($query->createCommand()->getRawSql());exit;
        return $dataProvider;
    }
}
