<?php

namespace backend\models\rbac;

use backend\models\AdminDb;
use Yii;
use common\components\validators\UniqueCustomValidator;
use yii\base\Model;

/**
 * This is the model class for table "auth_menu".
 *
 * @property string $name
 * @property string $description
 * @property integer $create_at
 * @property integer $sort
 * @property integer $level
 */
class AuthMenu extends AdminDb {

    public $moduleId;
    public $controllerId;
    public $actionId;
    public $_oldUniqueId;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'auth_menu';
    }


    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['controllerId', 'actionId'], 'required'],
            [['moduleId', 'controllerId', 'actionId'], 'string', 'max' => 20],
            ['name', 'default', 'value' => function($model, $attribute) {
                    $moduleId = $model->moduleId ? $model->moduleId . '/' : '';
                    return $moduleId . $model->controllerId . '/' . $model->actionId;
                }],
            ['sort', 'default', 'value' => 0],
            [['name', 'description', 'parent_id'], 'required'],
            [['create_at', 'sort', 'display'], 'integer'],
            [['name', 'description', 'parent_name', 'icon'], 'string', 'max' => 64],
            ['name', UniqueCustomValidator::className()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'name',
            'description' => '描述',
            'create_at' => '创建时间',
            'sort' => '排序',
            'display' => '是否显示',
        ];
    }

    /**
     * 获取一级和二级菜单
     * @param type $level
     * @return type
     */
    public function getMenus1And2() {
        $list = $this->find()->where(['parent_id' => 0])->orderBy('sort')->asArray()->all();

        $newarr = [];
        foreach ($list as $key => $val) {
            $newarr[$val['id']] = $val['description'];

            $list2 = $this->find()->where(['parent_id' => $val['id']])->orderBy('sort')->asArray()->all();
            if ($list2) {
                foreach ($list2 as $key2 => $val2) {
                    $newarr[$val2['id']] = " ┗━ " . $val2['description'];
                }
            }
        }

        return $newarr;
    }

    /**
     * 获取菜单列表
     * @param type $display 是否为显示的菜单，1显示，0不显示，默认为所有的，即包含1,0
     * @return type
     */
    public function getMenusAll($display = NULL) {
        $query = $this->find()->select(['id', 'name', 'description', 'sort', 'display', 'icon']);
        $query->andWhere(['parent_id' => 0]);
        if ($display) {
            $query->andWhere(['display' => $display]);
        }
        $list = $query->orderBy('sort')->asArray()->all();

        foreach ($list as $key => $val) {
            // 获取二级菜单
            $query2 = $this->find()->select(['id', 'name', 'description', 'sort', 'display', 'icon']);
            $query2->andWhere(['parent_id' => $val['id']]);
            if ($display) {
                $query2->andWhere(['display' => $display]);
            }
            $list2 = $query2->orderBy('sort asc, display desc')->asArray()->all();

            // 获取三级菜单
            foreach ($list2 as $key2 => $val2) {
                $query3 = $this->find()->select(['id', 'name', 'description', 'sort', 'display', 'icon']);
                $query3->andWhere(['parent_id' => $val2['id']]);
                if ($display) {
                    $query3->andWhere(['display' => $display]);
                }
                $list3 = $query3->orderBy('sort asc, display desc')->asArray()->all();

                $list2[$key2]['list3'] = $list3 ? : [];
            }

            $list[$key]['list2'] = $list2 ? : [];
        }

        return $list;
    }

    /**
     * 检测是否已经存在该菜单
     * @param type $name 检测的名称
     * @param type $notName 不包含的名称
     * @return type
     */
    public function hasName($name, $notName = NULL) {
        $query = $this->find();
        $query->andWhere(['name' => $name]);
        if ($notName) {
            $query->andWhere(['!=', 'name', $notName]);
        }

        return $query->one();
    }

}
