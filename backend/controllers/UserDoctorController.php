<?php

namespace backend\controllers;

use common\components\UploadForm;
use yii\helpers\Html;
use yii\web\UploadedFile;
use common\models\User;
use common\models\UserLogin;
use Yii;
use common\models\UserDoctor;
use common\models\UserDoctorSearchModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserDoctorController implements the CRUD actions for UserDoctor model.
 */
class UserDoctorController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all UserDoctor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new \backend\models\UserDoctorSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserDoctor model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserDoctor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $userInfo=new \common\models\UserDoctor();
        $userLogin=new UserLogin();
        $model->loadDefaultValues();
        $userInfo->loadDefaultValues();
        $userLogin->loadDefaultValues();
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            if($model->save()) {
                $userInfo->load(Yii::$app->request->post());

                $imagesFile = UploadedFile::getInstancesByName(Html::getInputName($userInfo, 'avatar'));
                if ($imagesFile) {
                    $upload = new UploadForm();
                    $upload->imageFiles = $imagesFile;
                    $image = $upload->upload();
                    $userInfo->avatar = $image[0];
                }
                $userInfo->userid = $model->id;
                if($userInfo->save()) {

                    $userLogin->userid = $model->id;
                    $userLogin->password = md5(md5($model->phone."2QH@6%3(87"));
                    $userLogin->save();
                    \Yii::$app->getSession()->setFlash('success');
                    return $this->redirect(['view', 'id' => $model->id]);

                }
            }
            \Yii::$app->getSession()->setFlash('error', 'userinfo:'.implode(',',$userInfo->firstErrors).'userLogin:'.implode(',',$userLogin->firstErrors).'model:'.implode(',',$model->firstErrors));
            return $this->redirect(['create']);
        }
        return $this->render('create', [
            'model' => $model,
            'userInfo' => $userInfo,
            'userLogin' => $userLogin,
        ]);
    }

    /**
     * Updates an existing UserDoctor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = User::findOne($id);
        $userInfo=\common\models\UserDoctor::findOne(['userid'=>$id]);
        $userInfo=$userInfo?$userInfo:new \common\models\UserDoctor;
        $userLogin=$model->login;
        $model->loadDefaultValues();
        $userInfo->loadDefaultValues();
        $userLogin->loadDefaultValues();
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            $userid=$model->save();
            $userInfo->load(Yii::$app->request->post());

            $imagesFile = UploadedFile::getInstancesByName(Html::getInputName($userInfo,'avatar'));
            if($imagesFile) {
                $upload= new UploadForm();
                $upload->imageFiles = $imagesFile;
                $image = $upload->upload();
                $userInfo->avatar = $image[0];
            }else{
                $userInfo->avatar=UserDoctor::findOne(['userid'=>$id])->avatar;
            }
            $userInfo->userid=$model->id;
            if($userInfo->save()) {

                $userLogin->password = md5(md5($model->phone."2QH@6%3(87"));
                $userLogin->save();
                \Yii::$app->getSession()->setFlash('success');
                return $this->redirect(['update', 'id' => $model->id]);

            }
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'userInfo' => $userInfo,
            'userLogin' => $userLogin,
        ]);

    }

    /**
     * Deletes an existing UserDoctor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserDoctor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserDoctor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserDoctor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
