<?php

namespace backend\controllers;

use backend\models\operate\AdminOperate;
use backend\models\operate\Operate;
use backend\models\rbac\AuthMenu;
use backend\models\rbac\Rbac;

class BaseController extends \yii\web\Controller {

    public function afterAction($action, $result)
    {
        $actionName = $action->id;
        $controller = $action->controller->id;
        if($actionName != 'index')
        {
            $menu_id = AuthMenu::find()->where(['name' => $controller.'/'.$actionName])->scalar();
            if(!empty($menu_id))
            {
                $operate = Operate::findOne($menu_id);
                if(!empty($operate))
                {
                    $model = new AdminOperate();
                    $model->operate_id = (int) $operate->primaryKey;
                    $model->operate_time = time();
                    $model->admin_id = (int) \Yii::$app->user->identity->getId();
                    $model->save();
                }
            }
        }

        return parent::afterAction($action, $result);
    }
}
