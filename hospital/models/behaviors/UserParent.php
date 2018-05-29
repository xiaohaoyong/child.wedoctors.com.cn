<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/3
 * Time: 下午4:53
 */
namespace hospital\models\behaviors;
use hospital\models\user\UserParentSearchModel;
use yii\base\Behavior;
use yii\db\ActiveRecord;




class UserParent extends Behavior
{
    public $userParent;
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    public function beforeValidate($event)
    {
        $this->userParent=$event->sender->userid?UserParentSearchModel::find():new UserParentSearchModel();
        // 处理器方法逻辑
    }
}