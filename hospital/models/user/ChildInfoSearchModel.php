<?php

namespace hospital\models\user;

use hospital\models\behaviors\UserParent;
use hospital\models\user\ChildInfo;
use common\models\User;
use hospital\controllers\ChildInfoController;
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



    public $child_type;

    public $username;
    public $userphone;

    public $birthdayS;
    public $birthdayE;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userid', 'birthday', 'createtime', 'level','admin','child_type'], 'integer'],
            [['docpartimeS','docpartimeE','birthdayS','birthdayE', 'username'], 'string'],
            [['userphone'], 'integer'],

            [['name'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'level' => '签约状态',
            'docpartimeS' => '签约时间',
            'docpartimeE' => '~',
            'birthdayS' => '出生日期',
            'birthdayE' => '~',
            'username' => '父母联系人姓名',
            'admin'=>'管理机构',
            'child_type' => '儿童年龄段',
            'userphone' => '父母联系人手机号'
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
        $query = self::find();

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
        if($this->child_type){
            $mouth = ChildInfo::getChildType($this->child_type);
            $query->andFilterWhere(['>=', 'child_info.birthday', $mouth['firstday']]);
            $query->andFilterWhere(['<=', 'child_info.birthday', $mouth['lastday']]);
        }
        if(Yii::$app->user->identity->county==1114)
        {
            $query->andFilterWhere(['>', '`child_info`.birthday', strtotime('-3 year')]);
        }

        $hospitalid=$this->admin?$this->admin:\Yii::$app->user->identity->hospital;
        $doctorid=\common\models\UserDoctor::findOne(['hospitalid'=>\Yii::$app->user->identity->hospital]);

        if($this->level==1){
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
            $query->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid->userid]);
            $query->andFilterWhere(['`doctor_parent`.`level`' => 1]);
            $query->andFilterWhere(['child_info.admin'=>$hospitalid]);
            $query->andFilterWhere(['`child_info`.`doctorid`' => $hospitalid]);
        }elseif($this->level==2){
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
            $query->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid->userid]);
            $query->andFilterWhere(['`doctor_parent`.`level`' => 1]);
            $query->andWhere(['`child_info`.`source`'=>0]);
        }elseif($this->level==3){
            $parentids=\common\models\DoctorParent::find()->select('parentid')->andFilterWhere(['`doctor_parent`.`doctorid`'=>$doctorid->userid])->andFilterWhere(['level'=>1])->column();
            $query->andFilterWhere(['>', '`child_info`.birthday', strtotime('-3 year')]);
            $query->andFilterWhere(['not in', '`child_info`.userid',$parentids]);
            $query->andWhere(['`child_info`.`source`'=>$hospitalid]);
            $query->andFilterWhere(['`child_info`.`admin`' => $hospitalid]);
        }else{
            $query->andFilterWhere(['>', '`child_info`.birthday', strtotime('-3 year')]);
            $query->andFilterWhere(['`child_info`.`source`' => $hospitalid]);
            $query->andFilterWhere(['`child_info`.`admin`' => $hospitalid]);
        }


        if($this->docpartimeS!=='' and $this->docpartimeS!==null){
            if($this->level!=1 && $this->level!=2) {
                $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
            }
            $state = strtotime($this->docpartimeS . " 00:00:00");
            $end = strtotime($this->docpartimeE . " 23:59:59");
            $query->andFilterWhere(['>', '`doctor_parent`.`createtime`', $state]);
            $query->andFilterWhere(['<', '`doctor_parent`.`createtime`', $end]);
        }

        if($this->birthdayE!=='' and $this->birthdayS!==null){
            $state = strtotime($this->birthdayS . " 00:00:00");
            $end = strtotime($this->birthdayE . " 23:59:59");
            $query->andFilterWhere(['>', '`birthday`', $state]);
            $query->andFilterWhere(['<', '`birthday`', $end]);
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

//        var_dump($query->createCommand()->getRawSql());exit;
        return $dataProvider;
    }
}
