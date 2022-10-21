<?php

namespace databackend\controllers;


use common\models\UserDoctor;

class BaseController extends \yii\web\Controller {
    private $notCheckAccess = ['/rbac/access-error', 'site/index'];

    private $ignore = [
        'site/login', 'site/logout','site/captcha'
    ];
    public $doctor;

    public function beforeAction($action)
    {
        parent::beforeAction($action);

//lrs 注释
        $this->doctor=UserDoctor::find()->andFilterWhere(['county'=> \Yii::$app->user->identity->county])->asArray()->all();
        $path = \Yii::$app->request->pathInfo;

        if (in_array($path, $this->ignore))
        {
            return true;
        }

        if(\Yii::$app->user->isGuest)
        {
            return $this->redirect(\Yii::$app->user->loginUrl)->send();
        }

        $moduleId = \Yii::$app->controller->module->id === \Yii::$app->id ? '' : \Yii::$app->controller->module->id . '/';
        $controllerId = \Yii::$app->controller->id . '/';
        $actionId = \Yii::$app->controller->action->id;

        $permissionName = $moduleId . $controllerId . $actionId;
        $notCheckAccess = join('|', $this->notCheckAccess);

        // 如果是登录或退出动作
        if (stripos('/site/login|/site/logout', $permissionName) !== false) {
            return true;
        }
        return true;
    }
}
