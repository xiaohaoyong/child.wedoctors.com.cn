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
    public $childname;

    public $userphone;

    public $birthdayS;
    public $birthdayE;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userid', 'birthday', 'createtime', 'level', 'admin', 'child_type'], 'integer'],
            [['docpartimeS', 'docpartimeE', 'birthdayS', 'birthdayE', 'username','childname'], 'string'],
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
        $attr['childname'] = '宝宝姓名';

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
    public function searchNew($params){
        $query = self::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        $hospitalid = $this->admin ? $this->admin : \Yii::$app->user->identity->hospital;
        $doctor = \common\models\UserDoctor::findOne(['hospitalid' => $hospitalid]);
        $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
        $query->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctor->userid]);
        $query->andFilterWhere(['`doctor_parent`.`level`' => 1]);

        $query->orderBy('`doctor_parent`.createtime desc');
        //var_dump($query->createCommand()->getRawSql());exit;
        return $dataProvider;
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
        if ($this->child_type) {
            $mouth = ChildInfo::getChildType($this->child_type);
            $query->andFilterWhere(['>=', 'child_info.birthday', $mouth['firstday']]);
            $query->andFilterWhere(['<=', 'child_info.birthday', $mouth['lastday']]);
        }

        $hospitalid = $this->admin ? $this->admin : \Yii::$app->user->identity->hospital;
        $doctorid = \common\models\UserDoctor::findOne(['hospitalid' => $hospitalid]);

        if (in_array($doctorid->county, [1105, 1106,1109,1116]) || $hospitalid == 110589) {
            $year = 6;
        } else {
            $year = 3;
        }


        if ($this->level == 1) {
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
            $query->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid->userid]);
            $query->andFilterWhere(['`doctor_parent`.`level`' => 1]);
            $query->andFilterWhere(['child_info.admin' => $hospitalid]);
            $query->andFilterWhere(['>', '`child_info`.birthday', strtotime("-$year year")]);
            //$query->andFilterWhere(['`child_info`.`doctorid`' => $hospitalid]);
        } elseif ($this->level == 2) {
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
            $query->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid->userid]);
            $query->andFilterWhere(['`doctor_parent`.`level`' => 1]);
            $query->andWhere(['!=','`child_info`.`source`' ,$hospitalid]);
        } elseif ($this->level == 3) {
            $parentids = \common\models\DoctorParent::find()->select('parentid')->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid->userid])->andFilterWhere(['level' => 1])->column();
            $query->andFilterWhere(['>', '`child_info`.birthday', strtotime("-$year year")]);
            $query->andFilterWhere(['not in', '`child_info`.userid', $parentids]);
            $query->andFilterWhere(['`child_info`.`admin`' => $hospitalid]);
        } elseif ($this->level == 4) {
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
            $query->andWhere(['<', '`child_info`.birthday', strtotime("-$year year")]);
            $query->orWhere(['child_info.admin' => 0]);

            $query->andWhere(['`child_info`.`source`' => $hospitalid]);
            $query->andWhere(['`doctor_parent`.`doctorid`' => $doctorid->userid]);
            $query->andWhere(['`doctor_parent`.`level`' => 1]);
        } else{
            if(!$this->childname) {
                $query->andFilterWhere(['>', '`child_info`.birthday', strtotime("-$year year")]);
            }
            $query->andFilterWhere(['`child_info`.`doctorid`' => $hospitalid]);
        }


        if ($this->docpartimeS !== '' and $this->docpartimeS !== null) {
            if ($this->level != 1 && $this->level != 2 && $this->level != 4) {
                $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
            }
            $state = strtotime($this->docpartimeS . " 00:00:00");
            $end = strtotime($this->docpartimeE . " 23:59:59");
            $query->andFilterWhere(['>', '`doctor_parent`.`createtime`', $state]);
            $query->andFilterWhere(['<', '`doctor_parent`.`createtime`', $end]);
        }

        if ($this->birthdayE !== '' and $this->birthdayS !== null) {
            $state = strtotime($this->birthdayS);
            $end = strtotime($this->birthdayE);
            $query->andFilterWhere(['>=', '`birthday`', $state]);
            $query->andFilterWhere(['<=', '`birthday`', $end]);
        }

        if ($this->username || $this->userphone) {

            $query->leftJoin('user_parent', '`user_parent`.`userid` = `child_info`.`userid`');
            if ($this->username) {
                $query->andWhere(['or', ['`user_parent`.`mother`' => $this->username], ['`user_parent`.`father`' => $this->username], ['`user_parent`.`field11`' => $this->username]]);
            }
            if ($this->userphone) {
                $query->andWhere(['or', ['`user_parent`.`mother_phone`' => $this->userphone], ['`user_parent`.`father_phone`' => $this->userphone], ['`user_parent`.`field12`' => $this->userphone]]);
            }
        }

        if($this->childname){
            $query->andWhere(['child_info.name'=>$this->childname]);
        }
        if ($this->level == 1) {
            $query->orderBy('`doctor_parent`.createtime desc');

        } else {
            $query->orderBy([self::primaryKey()[0] => SORT_DESC]);
        }

        //var_dump($query->createCommand()->getRawSql());exit;
        return $dataProvider;
    }

    public function searchUnDone($params)
    {
        $query = self::find();
// add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        $doctorid = UserDoctor::findOne(['hospitalid' => \Yii::$app->user->identity->hospitalid])->userid;

        $query->andFilterWhere(['>', '`child_info`.birthday', strtotime('-3 year')]);
        $query->andFilterWhere(['child_info.admin' => \Yii::$app->user->identity->hospitalid]);

        if ($this->level == 1) {
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
            $query->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid]);
            $query->andFilterWhere(['`doctor_parent`.`level`' => 1]);
            $query->andFilterWhere(['child_info.admin' => \Yii::$app->user->identity->hospitalid]);
            $query->andFilterWhere(['`child_info`.`doctorid`' => \Yii::$app->user->identity->hospitalid]);
        }

        $query->leftJoin('examination', '`examination`.`childid` = `child_info`.`id`');
        if(!$this->child_type) {
            $query->andFilterWhere(['or',
                //['and', ['<', 'child_info.birthday', strtotime('-1 month')], ['not in', 'examination.field82', ['其他体检']]],
                ['and', ['<', 'child_info.birthday', strtotime('-1 month')], ['>','child_info.birthday', strtotime('-3 month')], ['not in', 'examination.field82', ['2-3个月体检']]],
                ['and', ['<', 'child_info.birthday', strtotime('-5 month')], ['>','child_info.birthday', strtotime('-6 month')], ['not in', 'examination.field82', ['5-6个月体检']]],
                ['and', ['<', 'child_info.birthday', strtotime('-8 month')], ['>','child_info.birthday', strtotime('-9 month')], ['not in', 'examination.field82', ['8-9个月体检']]],
                ['and', ['<', 'child_info.birthday', strtotime('-11 month')],['>', 'child_info.birthday', strtotime('-12 month')], ['not in', 'examination.field82', ['11-12个月体检']]],
                ['and', ['<', 'child_info.birthday', strtotime('-12 month')],['>', 'child_info.birthday', strtotime('-18 month')], ['not in', 'examination.field82', ['1岁半体检']]],
                ['and', ['<', 'child_info.birthday', strtotime('-18 month')],['>', 'child_info.birthday', strtotime('-24 month')], ['not in', 'examination.field82', ['2岁体检']]],
                ['and', ['<', 'child_info.birthday', strtotime('-24 month')],['>', 'child_info.birthday', strtotime('-30 month')], ['not in', 'examination.field82', ['2岁半体检']]],
                ['and', ['<', 'child_info.birthday', strtotime('-30 month')],['>', 'child_info.birthday', strtotime('-36 month')], ['not in', 'examination.field82', ['2岁11个月-3岁0个月体检']]],
                ['and', ['<', 'child_info.birthday', strtotime('-36 month')],['>', 'child_info.birthday', strtotime('-42 month')], ['not in', 'examination.field82', ['3岁体检']]],
                ['and', ['<', 'child_info.birthday', strtotime('-42 month')],['>', 'child_info.birthday', strtotime('-48 month')], ['not in', 'examination.field82', ['4岁体检']]],
            ]);
        }else{
            if($this->child_type==1){
                $query->andFilterWhere(['and', ['>', 'child_info.birthday', strtotime('-1 month')],['<', 'child_info.birthday', strtotime('-3 month')], ['not in', 'examination.field82', ['2-3个月体检']]]);
            }elseif($this->child_type==2){
                $query->andFilterWhere(['and', ['>', 'child_info.birthday', strtotime('-5 month')],['<', 'child_info.birthday', strtotime('-6 month')], ['not in', 'examination.field82', ['5-6个月体检']]]);
            }elseif($this->child_type==3){
                $query->andFilterWhere(['and', ['>', 'child_info.birthday', strtotime('-8 month')], ['<', 'child_info.birthday', strtotime('-9 month')], ['not in', 'examination.field82', ['8-9个月体检']]]);
            }elseif($this->child_type==4){
                $query->andFilterWhere(['and', ['>', 'child_info.birthday', strtotime('-11 month')], ['<', 'child_info.birthday', strtotime('-12 month')], ['not in', 'examination.field82', ['11-12个月体检']]]);
            }elseif($this->child_type==5){
                $query->andFilterWhere(['and', ['>', 'child_info.birthday', strtotime('-12 month')], ['<', 'child_info.birthday', strtotime('-18 month')], ['not in', 'examination.field82', ['1岁半体检']]]);
            }elseif($this->child_type==6){
                $query->andFilterWhere(['and', ['>', 'child_info.birthday', strtotime('-18 month')], ['<', 'child_info.birthday', strtotime('-24 month')], ['not in', 'examination.field82', ['2岁体检']]]);
            }elseif($this->child_type==7){
                $query->andFilterWhere(['and', ['>', 'child_info.birthday', strtotime('-24 month')], ['<', 'child_info.birthday', strtotime('-30 month')], ['not in', 'examination.field82', ['2岁半体检']]]);
            }elseif($this->child_type==8){
                $query->andFilterWhere(['and', ['>', 'child_info.birthday', strtotime('-30 month')], ['<', 'child_info.birthday', strtotime('-36 month')], ['not in', 'examination.field82', ['2岁11个月-3岁0个月体检']]]);
            }elseif($this->child_type==9){
                $query->andFilterWhere(['and', ['>', 'child_info.birthday', strtotime('-36 month')], ['<', 'child_info.birthday', strtotime('-42 month')], ['not in', 'examination.field82', ['3岁体检']]]);
            }elseif($this->child_type==10){
                $query->andFilterWhere(['and', ['>', 'child_info.birthday', strtotime('-42 month')], ['<', 'child_info.birthday', strtotime('-48 month')], ['not in', 'examination.field82', ['4岁体检']]]);
            }
        }
        $query->groupBy('child_info.id');
        $query->orderBy('child_info.birthday desc');
        //echo $query->createCommand()->getRawSql();exit;
        return $dataProvider;

    }


}
