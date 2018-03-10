<?php

namespace weixin\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "child_health_record".
 *
 * @property string $id
 * @property integer $childid
 * @property integer $createtime
 * @property integer $userid
 * @property string $content
 */
class ChildHealthRecord extends \common\models\ChildHealthRecord {

    /**
     * 添加
     * @param array $data  
     * @return array 对象 
     * slx
     */
    public static function addData($data) {
        $model = new self();
        if ($model->load($data)) {
            $model->save();
        }
        return $model;
    }

    /**
     * 儿童健康记录
     *
     * @param string $childid
     * @return 数组
     */
    public static function GetListByChildid($childid, $page = 1, $pageSize = 10) {
        $data = static::find();
        if (!empty($childid)) {
            $data->andWhere(['childid' => $childid]);
        }
        $data->orderBy('createtime desc');
        //总共多少页
        $results['countPages'] = ceil($data->count() / $pageSize);
        if ($page > $results['countPages']) {
            $results['list'] = '';
            return $results;
        }
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pageSize]);
        $datas = $data->offset($pages->offset)->limit($pages->limit)->all();
        foreach ($datas as $key => $val) {
            $results['list'][$key] = self::findById($val->id);
        }
        return $results;
    }

    /**
     *  儿童健康id查
     * @param string $id
     * @return 数组
     * slx
     */
    public static function findById($id) {
        $data = self::findOne($id);
        if (empty($data)) {
            return [];
        }
        $results['content'] = $data->content;
        $results['createdata'] = date('Y/m/d', $data->createtime);
        $results['userid'] = $data->userid;
        $UserData = User::findById($data->userid);
        $results['username'] = '家长';
        if ($UserData->type == 0) {
            $DoctorData = \common\models\UserDoctor::GetOneById($data->userid);
            $results['username'] = !empty($DoctorData['name']) ? $DoctorData['name'] : '未知';
        }
        return $results;
    }

}
