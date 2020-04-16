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
class ChildSearch extends ChildInfo
{
    /*
     *   <?= $form->field($model, 'childName')?>
    <?= $form->field($model, 'parentName')?>
    <?= $form->field($model, 'phone')?>
    <?= $form->field($model, 'birthday')?>
     */


    public $phone;
    public $parentName;
    public $birthdayS;
    public $birthdayE;
    public $docpartimeS;
    public $docpartimeE;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['docpartimeS', 'docpartimeE', 'birthdayS', 'birthdayE'], 'string'],
            [['phone'], 'integer'],
            [['parentName'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        $attr = parent::attributeLabels();
        $attr['docpartimeS'] = '签约时间';
        $attr['docpartimeE'] = '~';
        $attr['birthdayS'] = '出生日期';
        $attr['birthdayE'] = '~';
        $attr['parentName'] = '父母联系人姓名';
        $attr['phone'] = '联系电话';
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
    public function search($params){
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

        if ($this->docpartimeS !== '' and $this->docpartimeS !== null) {
            $state = strtotime($this->docpartimeS . " 00:00:00");
            $end = strtotime($this->docpartimeE . " 23:59:59");
            $query->andFilterWhere(['>=', '`doctor_parent`.`createtime`', $state]);
            $query->andFilterWhere(['<=', '`doctor_parent`.`createtime`', $end]);
        }

        if ($this->birthdayE !== '' and $this->birthdayS !== null) {
            $state = strtotime($this->birthdayS);
            $end = strtotime($this->birthdayE);
            $query->andFilterWhere(['>=', '`birthday`', $state]);
            $query->andFilterWhere(['<=', '`birthday`', $end]);
        }



        $query->orderBy('`doctor_parent`.createtime desc');
        var_dump($query->createCommand()->getRawSql());exit;
        return $dataProvider;
    }
}
