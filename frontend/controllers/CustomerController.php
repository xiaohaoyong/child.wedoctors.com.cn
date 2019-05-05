<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/5/5
 * Time: 下午1:55
 */

namespace frontend\controllers;


use common\models\Customer;
use yii\bootstrap\ActiveForm;
use yii\web\Controller;
use Yii;
use yii\web\Response;

class CustomerController extends Controller
{
    public function actionPut(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $params = Yii::$app->request->post();
        $model = new Customer();

        if (Yii::$app->request->isPost && $model->load($params)) {
            return ['success' => $model->save()];
        }
        else{
            return ['code'=>'error'];
        }

    }
    public function actionValidateForm ()
    {
        $model = new Customer();
        $request = \Yii::$app->getRequest();
        if ($request->isPost && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }
}