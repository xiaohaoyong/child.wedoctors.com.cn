<?php

namespace databackend\controllers;

use app\components\UploadForm;
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

    public function actionTest()
    {
        //获取域名或主机地址
        echo $_SERVER['HTTP_HOST']."<br>"; #localhost

        //获取网页地址
        echo $_SERVER['PHP_SELF']."<br>"; #/blog/testurl.php

        //获取网址参数
        echo $_SERVER["QUERY_STRING"]."<br>"; #id=5

        //获取用户代理
        echo $_SERVER['HTTP_REFERER']."<br>";

        //获取完整的url
        echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."<br>";
        echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']."<br>";
        #http://localhost/blog/testurl.php?id=5

        //包含端口号的完整url
        echo 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]."<br>";
        #http://localhost:80/blog/testurl.php?id=5

        //只取路径
        $url='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]."<br>";
        echo dirname($url);
        #http://localhost/blog
    }
    /**
     * Lists all UserDoctor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new \databackend\models\user\UserDoctorSearchModel();
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
        $userInfo=new \databackend\models\user\UserDoctor();
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
                    return $this->redirect(['update', 'id' => $model->id]);

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
        $userInfo=\databackend\models\user\UserDoctor::findOne(['userid'=>$id]);
        $userInfo=$userInfo?$userInfo:new \databackend\models\user\UserDoctor();

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
            $userInfo->save();
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
