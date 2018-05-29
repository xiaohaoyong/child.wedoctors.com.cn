<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/3
 * Time: 下午4:53
 */
namespace hospital\models\behaviors;
use hospital\models\user\DoctorParentSearchModel;
use yii\base\Behavior;
use yii\db\ActiveRecord;



class DoctorParent extends Behavior
{
    public $doctorParent;
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    public function beforeValidate($event)
    {

        $this->doctorParent=$event->sender->userid?DoctorParentSearchModel::find():new DoctorParentSearchModel();
        // 处理器方法逻辑
    }
}