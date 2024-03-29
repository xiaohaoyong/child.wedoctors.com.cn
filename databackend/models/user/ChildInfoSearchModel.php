<?php

namespace databackend\models\user;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

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
    public $admin;
    public $child_type;

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
        $attr = parent::attributeLabels();


        $attr['level'] = '签约状态';
        $attr['docpartimeS'] = '签约时间';
        $attr['docpartimeE'] = '~';
        $attr['birthdayS'] = '出生日期';
        $attr['birthdayE'] = '~';
        $attr['username'] = '父母联系人姓名';
        $attr['admin'] = '管理机构';
        $attr['child_type'] = '儿童年龄段';
        $attr['userphone'] = '父母联系人手机号';
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
        $query = \common\models\ChildInfo::find();

        // add conditions that should always apply here
        $doctor = UserDoctor::find()->andFilterWhere(['county' => \Yii::$app->user->identity->county])->asArray()->all();
        $doctorids=ArrayHelper::getColumn($doctor,'userid');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);


        if($this->child_type){
            $mouth = ChildInfo::getChildType($this->child_type);
            $query->andFilterWhere(['>=', 'child_info.birthday', $mouth['firstday']]);
            $query->andFilterWhere(['<=', 'child_info.birthday', $mouth['lastday']]);
        }



        if(!$this->admin) {
            $hospitalids=ArrayHelper::getColumn($doctor,'hospitalid');

        }else{
            $hospitalids=[$this->admin];
            $doctor = UserDoctor::find()->andFilterWhere(['in','hospitalid',$hospitalids])->asArray()->all();
            $doctorids=ArrayHelper::getColumn($doctor,'userid');
        }


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if($this->level==1){
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
            $query->andFilterWhere(['>', '`child_info`.birthday', strtotime('-3 year')]);

            $query->andFilterWhere(['in','`doctor_parent`.`doctorid`',$doctorids]);
            $query->andFilterWhere(['`doctor_parent`.`level`' => 1]);
            $query->andFilterWhere(['in', '`child_info`.admin',$hospitalids]);
        }elseif($this->level==2){
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
            $query->andFilterWhere(['in','`doctor_parent`.`doctorid`',$doctorids]);
            $query->andFilterWhere(['`doctor_parent`.`level`' => 1]);
            $query->andFilterWhere(['in', '`child_info`.doctorid',$hospitalids]);
            $query->andWhere(['child_info.source'=>0]);
        }elseif($this->level==3){

            $parentids=\common\models\DoctorParent::find()->select('parentid')->andFilterWhere(['in','`doctor_parent`.`doctorid`',$doctorids])->andFilterWhere(['level'=>1])->column();
            $query->andFilterWhere(['>', '`child_info`.birthday', strtotime('-3 year')]);
            $query->andFilterWhere(['not in', '`child_info`.userid',$parentids]);
            $query->andFilterWhere(['in', '`child_info`.source',$hospitalids]);
            $query->andFilterWhere(['in', '`child_info`.admin',$hospitalids]);

        }elseif($this->level==4){
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
            $query->andWhere(['<', '`child_info`.birthday', strtotime('-3 year')]);
            $query->orWhere(['child_info.admin'=>0]);

            $query->andFilterWhere(['in', '`child_info`.source',$hospitalids]);
            $query->andFilterWhere(['in','`doctor_parent`.`doctorid`',$doctorids]);
            $query->andWhere(['`doctor_parent`.`level`' => 1]);
        }else{
            $query->andFilterWhere(['>', '`child_info`.birthday', strtotime('-3 year')]);
            $query->andFilterWhere(['in', '`child_info`.source',$hospitalids]);
            $query->andFilterWhere(['in', '`child_info`.admin',$hospitalids]);
        }

        if($this->docpartimeS!=='' and $this->docpartimeS!==null){
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

        //var_dump($query->createCommand()->getRawSql());exit;
        return $dataProvider;
    }
}
