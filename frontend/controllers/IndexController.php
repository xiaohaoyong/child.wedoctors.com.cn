<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/4/26
 * Time: 上午11:42
 */

namespace frontend\controllers;


use yii\web\Controller;

class IndexController extends Controller
{
    public function actionIndex(){


        return $this->render('index');
    }
    public function actionView(){


        return $this->render('view');
    }
    public function actionAbout(){


        return $this->render('about');
    }
}