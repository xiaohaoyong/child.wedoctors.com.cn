<?php

namespace backend\controllers;

use backend\models\Push;
use Yii;
use common\models\Chain;
use backend\models\ChainSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChainController implements the CRUD actions for Chain model.
 */
class PushController extends Controller
{

    /**
     * Lists all Chain models.
     * @return mixed
     */
    public function actionIndex()
    {

        return $this->render('index');
    }


}
