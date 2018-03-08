<?php

namespace weixin\models;

use Yii;

/**
 * This is the model class for table "doctor_parent".
 *
 * @property string $id
 * @property string $doctorid
 * @property integer $parentid
 * @property string $createtime
 * @property integer $level
 */
class DoctorParent extends \common\models\DoctorParent {

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
     * 儿宝顾问
     *
     * @param string $parentid
     * @return 数组
     */
    public static function findByParentid($parentid = null, $doctorid = null) {
        $data = self::find();
        if (!empty($parentid)) {
            $data->andWhere(['parentid' => $parentid]);
        }
        if (!empty($doctorid)) {
            $data->andWhere(['doctorid' => $doctorid]);
        }
        $datas = $data->one();
        if (empty($datas)) {
            return [];
        }
        //医生详情
        $result = UserDoctor::GetOneById($datas->doctorid);
        $result['level'] = $datas->level;
        $result['createtime'] = $datas->createtime;
        return $result;
    }

}
