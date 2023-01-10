<?php

namespace hospital\controllers;


use hospital\models\Doctors;

class BaseController extends \yii\web\Controller {
    private $notCheckAccess = ['/rbac/access-error', 'site/index'];

    private $ignore = [
        'appoint-calling/list-new','appoint-calling/text','appoint-calling/list','appoint-calling/ttl','site/hospitals','site/login', 'site/logout','site/captcha','site/code','site/code','synchronization/data-callback','xlsxoutof/data-callback'
    ];
    private $nocsrf=[
        'synchronization/data-callback','xlsxoutof/data-callback'
    ];

    public function beforeAction($action)
    {

        $path = \Yii::$app->request->pathInfo;

        $moduleId = \Yii::$app->controller->module->id === \Yii::$app->id ? '' : \Yii::$app->controller->module->id . '/';
        $controllerId = \Yii::$app->controller->id . '/';
        $actionId = \Yii::$app->controller->action->id;

        $permissionName = $moduleId . $controllerId . $actionId;
        $notCheckAccess = join('|', $this->notCheckAccess);



        if(in_array($path, $this->nocsrf)){
            $action->controller->enableCsrfValidation = false;
        }
        parent::beforeAction($action);

        if (in_array($path, $this->ignore))
        {
            return true;
        }
        $redirect = [];
        if (stripos('appoint-calling/room', $permissionName) !== false) {
            $redirect = ['redirect'=>'appoint-calling/room'];
        }

            if(\Yii::$app->user->isGuest)
        {
            return $this->redirect(\Yii::$app->user->loginUrl+$redirect)->send();
        }
        if(!\Yii::$app->user->identity->hospital){
            \Yii::$app->user->logout();
            return $this->redirect(\Yii::$app->user->loginUrl+$redirect)->send();
        }
        $doctors=Doctors::findOne(['userid'=>\Yii::$app->user->identity->userid]);
        if($doctors && $doctors->type){
            $t=(string)decbin($doctors->type);
            $c=strlen($t);
            for($i=0;$i<$c;$i++){
                if((string)$t[$i]==1) {
                    $d[] = pow(10, $i);
                }
            }

            if(!in_array(1,$d)){
                \Yii::$app->user->logout();
                return $this->redirect(\Yii::$app->user->loginUrl+$redirect)->send();
            }
        }else{
            \Yii::$app->user->logout();
            return $this->redirect(\Yii::$app->user->loginUrl+$redirect)->send();
        }


        // 如果是登录或退出动作
        if (stripos('/site/login|/site/logout', $permissionName) !== false) {
            return true;
        }
        return true;
    }
}
