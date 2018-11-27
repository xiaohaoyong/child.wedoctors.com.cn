<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WeOpenid;

/**
 * WeOpenidSearch represents the model behind the search form about `common\models\WeOpenid`.
 */
class WeOpenidSearch extends WeOpenid
{
    public $docpartimeS;
    public $docpartimeE;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'createtime', 'doctorid', 'level'], 'integer'],
            [['openid', 'unionid', 'xopenid'], 'safe'],
            [['docpartimeS','docpartimeE'], 'string'],

        ];
    }
    public function attributeLabels()
    {

        $return = parent::attributeLabels();
        $return['docpartimeS'] = '签约时间';
        $return['docpartimeE'] = '~';
        return $return;
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
        $query = WeOpenid::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'doctorid' => $this->doctorid,
            'level' => $this->level,
            'openid' => $this->openid,
            'unionid' => $this->unionid,
            'xopenid' => $this->xopenid,
        ]);

        if($this->docpartimeS!=='' and $this->docpartimeS!==null){
            $state = strtotime($this->docpartimeS . " 00:00:00");
            $end = strtotime($this->docpartimeE . " 23:59:59");
            $query->andFilterWhere(['>', 'createtime', $state]);
            $query->andFilterWhere(['<', 'createtime', $end]);
        }
        $query->orderBy([self::primaryKey()[0]=>SORT_DESC]);

        return $dataProvider;
    }
}
