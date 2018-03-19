<?php

namespace weixin\models;

use Yii;
use common\models\Subject;

/**
 * This is the model class for table "user_doctor".
 *
 * @property string $userid
 * @property string $name
 * @property integer $sex
 * @property integer $age
 * @property string $birthday
 * @property string $phone
 * @property string $hospitalid
 * @property string $subject_b
 * @property string $subject_s
 * @property integer $title
 * @property string $intro
 * @property string $avatar
 * @property string $skilful
 * @property string $idnum
 * @property integer $province
 * @property string $county
 * @property integer $city
 * @property integer $atitle
 * @property string $otype
 * @property string $authimg
 */
class UserDoctor extends \common\models\UserDoctor {

    /**
     * 获取医生个人信息
     * @param string $id
     * @return 数组
     * slx
     */
    public static function GetOneById($id) {
        $data = UserDoctor::find()->where(['userid' => $id])->joinwith('hospital')->one();
        if (empty($data)) {
            return [];
        }
        $result = $data->toArray();
        //科室
        $result['subject_bname'] = Subject::$subject[$data->subject_b];
        $result['subject_sname'] = Subject::$subject[$data->subject_s];
        //职称
        $result['titlename'] = \common\models\UserDoctor::$titleText[$data->title];
        //医院
        $result['hospital'] = $data->hospital->toArray();
        $result['phone']=$data->phone;
        return $result;
    }

}
