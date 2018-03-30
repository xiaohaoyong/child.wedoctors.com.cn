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
            [['id', 'userid', 'birthday', 'createtime'], 'integer'],
            [['name'], 'safe'],
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
        $doctorParent=$this->doctorParent->search($params);

        if(isset($this->doctorParent->level) || isset($this->doctorParent->createtime)) {
            $query->andFilterWhere(['in', 'userid', $doctorParent->query->select('parentid')->column()]);
        }
        //var_dump($query->createCommand()->getRawSql());exit;

        //var_dump($doctorParent->query->select('parentid')->column());exit;
        $userDoctor=$this->userParent->search($params);
        if($this->userParent->field11 || $this->userParent->field12) {
            $query->andFilterWhere(['in', 'userid', $userDoctor->query->select('userid')->column()]);
        }
        $query->andFilterWhere(['source'=>\Yii::$app->user->identity->hospital]);


        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
