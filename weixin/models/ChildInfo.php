<?php

namespace weixin\models;

use Yii;

/**
 * This is the model class for table "{{%child_info}}".
 * @property string $id
 * @property string $userid
 * @property string $name
 * @property integer $birthday
 * @property string $createtime
 */
class ChildInfo extends \common\models\ChildInfo {

    /**
     * 添加/修改
     * @param array $data  
     * @return array 对象 
     * slx
     */
    public static function addData($data, $id = NULL) {
        $model = self::findOne($id);
        if (empty($model)) {
            $model = new self();
        }
        if ($model->load($data)) {
            $model->save();
        }
        return $model;
    }

    /**
     * userid查宝宝列表
     *
     * @param string $userid
     * @return 数组
     */
    public static function GetList($userid) {
        $data = self::find()->where(['userid' => $userid])->all();
        $result = [];
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                $result[$key] = self::findById($val->id);
            }
        }
        return $result;
    }

    /**
     * userid查用户
     *
     * @param string $id
     * @return 数组
     */
    public static function findById($id) {
        $data = static::findOne($id);
        if (empty($data)) {
            return [];
        }
        $ParentData = UserParent::GetInfoByChildid($data->userid);
        $result = $data->toArray();
        $result['parent'] = [];
        $result['gender'] = $data->gender == 1 ? '男' : '女';
        $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $data->birthday));
        $result['age'] = $DiffDate[0] . '岁' . $DiffDate[1] . '个月' . $DiffDate[2] . '天';
        $UserData = User::findById($result['userid']);
        $result['phone'] = $UserData->phone;
        if (!empty($ParentData)) {
            $result['parent'] = $ParentData->toArray();
        }
        return $result;
    }

}
