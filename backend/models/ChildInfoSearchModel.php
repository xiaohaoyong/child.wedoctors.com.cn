<?php

namespace backend\models;

use common\models\UserDoctor;
use databackend\models\behaviors\UserParent;
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
            [['id', 'userid', 'birthday', 'createtime', 'level', 'admin', 'child_type'], 'integer'],
            [['docpartimeS', 'docpartimeE', 'birthdayS', 'birthdayE', 'username'], 'string'],
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
            $mouth = \common\models\ChildInfo::getChildType($this->child_type);
            $query->andFilterWhere(['>=', 'child_info.birthday', $mouth['firstday']]);
            $query->andFilterWhere(['<=', 'child_info.birthday', $mouth['lastday']]);
        }

        $hospitalid = $this->admin;
        if($hospitalid) {
            $doctorid = \common\models\UserDoctor::findOne(['hospitalid' => $hospitalid]);
        }

        if ($this->level == 1) {
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
            if($doctorid) {
                $query->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid->userid]);
            }
            $query->andFilterWhere(['`doctor_parent`.`level`' => 1]);
            $query->andFilterWhere(['child_info.admin' => $hospitalid]);
            $query->andFilterWhere(['`child_info`.`doctorid`' => $hospitalid]);
        } elseif ($this->level == 2) {
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
            if($doctorid) {
                $query->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid->userid]);
            }            $query->andFilterWhere(['`doctor_parent`.`level`' => 1]);
            $query->andWhere(['`child_info`.`source`' => 0]);
        } elseif ($this->level == 3) {
            $parent = \common\models\DoctorParent::find()->select('parentid');
            if($doctorid) {
                $parent->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid->userid]);
            }
            $parent->andFilterWhere(['level' => 1]);


            $parentids=$parent->column();
            $query->andFilterWhere(['not in', '`child_info`.userid', $parentids]);
            if($doctorid) {
                $query->andFilterWhere(['`child_info`.`admin`' => $hospitalid]);
            }
        } elseif ($this->level == 4) {
            $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
            $query->orWhere(['child_info.admin' => 0]);
            if($doctorid) {
                $query->andWhere(['`child_info`.`source`' => $hospitalid]);
                $query->andWhere(['`doctor_parent`.`doctorid`' => $doctorid->userid]);
            }
            $query->andWhere(['`doctor_parent`.`level`' => 1]);
        } else {
            if($doctorid) {
                $query->andFilterWhere(['`child_info`.`admin`' => $hospitalid]);
            }
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

        //var_dump($query->createCommand()->getRawSql());exit;
        return $dataProvider;
    }
}
