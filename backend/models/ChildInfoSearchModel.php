<?php

namespace backend\models;

use common\models\UserDoctor;
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
            'username' => '姓名',
            'admin'=>'管理机构',
            'userphone' => '手机号'
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
        if($this->admin) {
            $query->andFilterWhere(['`child_info`.`doctorid`' => $this->admin]);
        }

        if ($this->level || ($this->docpartimeS!=='' and $this->docpartimeS!==null)) {
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
        }

        if($this->level==1)
        {
            $query->andFilterWhere(['`doctor_parent`.`level`' => $this->level]);
        }
        if($this->level==3){
            $query->andWhere(['or',['<>','`doctor_parent`.`level`' ,1],['`doctor_parent`.`parentid`'=>null]]);
        }


        if($this->level==2)
        {
            $query->andFilterWhere(['<=','`child_info`.`source`',38]);
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
                $query->andWhere(['or',['`user_parent`.`mother`' => $this->username],['`user_parent`.`father`' => $this->username],['`user_parent`.`field11`' => $this->username]]);
            }
            if ($this->userphone) {
                $query->andWhere(['or',['`user_parent`.`mother_phone`' => $this->userphone],['`user_parent`.`father_phone`' => $this->userphone],['`user_parent`.`field12`' => $this->userphone]]);

            }
        }
        if($this->level==1) {
            $query->orderBy('`doctor_parent`.createtime desc');

        }else {
            $query->orderBy([self::primaryKey()[0] => SORT_DESC]);
        }

        //var_dump($query->createCommand()->getRawSql());exit;
        return $dataProvider;
    }
}
