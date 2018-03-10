<?php

namespace backend\models\rbac;

use backend\models\AdminDb;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "auth_menu".
 *
 * @property string $name
 * @property string $description
 * @property integer $create_at
 * @property integer $sort
 * @property integer $level
 */
class AuthMenuSearch extends AdminDb {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // 旁路在父类中实现的 scenarios() 函数
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = AuthMenu::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // 从参数的数据中加载过滤条件，并验证
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // 增加过滤条件来调整查询对象
        $query->andFilterWhere(['name' => $this->name]);

        return $dataProvider;
    }
}
