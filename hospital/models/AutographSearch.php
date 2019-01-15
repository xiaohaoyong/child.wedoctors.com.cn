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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'createtime', 'loginid', 'userid'], 'integer'],
            [['img'], 'safe'],
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
        $t = $params['t'];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $userDoctor = UserDoctor::findOne(['hospitalid' => Yii::$app->user->identity->hospitalid]);
        $dp = DoctorParent::find()->select('parentid')
            ->andFilterWhere(['doctorid' => $userDoctor->userid]);


        if ($t) {
            $dp->leftJoin('pregnancy', '`pregnancy`.`familyid` = `doctor_parent`.`parentid`');
            $dp->andWhere(['>', 'familyid', 0]);
            $dp->andWhere(['field49'=>0]);
        }

        $doctorParent = $dp->column();
        if (!$doctorParent) {
            $doctorParent = [0];
        }
        $query->andWhere(['in', 'userid', $doctorParent]);
        $query->orderBy([self::primaryKey()[0] => SORT_DESC]);
        echo $query->createCommand()->getRawSql();
        return $dataProvider;
    }
}
