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
    public $docpartimeS;
    public $docpartimeE;

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
            [['docpartimeS','docpartimeE', 'username'], 'string'],
            [['userphone'], 'integer'],

            [['name'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'level' => '签约',
            'docpartimeS' => '签约时间',
            'docpartimeE' => '~',
            'username' => '母亲姓名',
            'admin'=>'管理机构',
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
        if (($this->level!=='' and $this->level!==null) || ($this->docpartimeS!=='' and $this->docpartimeS!==null)) {
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
        }

        //管理机构
        if(\Yii::$app->user->identity->type != 1){
            $hospital=\Yii::$app->user->identity->hospital;
        }elseif($this->admin)
        {
            $hospital=$this->admin;
        }else {
            $hospital=0;
        }
        if($hospital && !$this->level) {
            // grid filtering conditions
            $query->andFilterWhere(['source' => $hospital,]);
        }
        if($this->level!=='' and $this->level!==null)
        {
            $query->andFilterWhere(['`doctor_parent`.`level`' => $this->level]);
            if($hospital) {
                $doctorid = UserDoctor::findOne(['hospitalid' => $hospital])->userid;
                $query->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid]);
            }
        }
        if($this->docpartimeS!=='' and $this->docpartimeS!==null){
            $state = strtotime($this->docpartimeS . " 00:00:00");
            $end = strtotime($this->docpartimeE . " 23:59:59");
            $query->andFilterWhere(['>', '`doctor_parent`.`createtime`', $state]);
            $query->andFilterWhere(['<', '`doctor_parent`.`createtime`', $end]);
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
        $query->orderBy([self::primaryKey()[0] => SORT_DESC]);


        //var_dump($query->createCommand()->getRawSql());exit;
        return $dataProvider;
    }
}
