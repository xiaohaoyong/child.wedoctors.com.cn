<?php

namespace backend\controllers;

use backend\models\Push;
use common\components\UploadForm;
use common\models\ArticleInfo;
use Yii;
use backend\models\Article;
use backend\models\ArticleSearchModel;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends BaseController
{

    public function actions()
    {
        return [
            'Kupload' => [
                'class' => 'pjkui\kindeditor\KindEditorAction',
            ]
        ];
    }
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
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new \backend\models\ArticleSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExamine(){

        $searchModel = new \backend\models\ArticleSearchModel();
        $queryParams=Yii::$app->request->queryParams;
        $queryParams['ArticleSearchModel']['level']=-1;
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('examine', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionRelease(){

        $searchModel = new \backend\models\ArticleSearchModel();
        $queryParams=Yii::$app->request->queryParams;
        $queryParams['ArticleSearchModel']['level']=0;
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('release', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionPublish($id){
        $model=$this->findModel($id);
        if($model){
            $model->level=1;
            $model->save();
        }

        return $this->redirect(['release']);
    }


    public function actionVerify($id,$t)
    {
        $model=$this->findModel($id);
        if($model){
            $model->level=$t==1?0:-2;
            $model->save();
        }

        return $this->redirect(['examine']);
    }

    /**
     * Displays a single Article model.
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
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new \backend\models\Article();
        $article= new ArticleInfo();

        if ($model->load(Yii::$app->request->post()) && $article->load(Yii::$app->request->post())) {

            if($model->save())
            {
                $article->id=$model->id;
                $imagesFile = UploadedFile::getInstancesByName(Html::getInputName($article,'img'));
                if($imagesFile) {
                    $upload= new UploadForm();
                    $upload->imageFiles = $imagesFile;
                    $image = $upload->upload();
                    $article->img = $image[0];
                }

                if($article->save()) {
                    \Yii::$app->getSession()->setFlash('success');

                    return $this->redirect(['view', 'id' => $model->id]);
                }

            }
            \Yii::$app->getSession()->setFlash('error', implode(',',$model->firstErrors).implode(',',$article->firstErrors));
        }
        return $this->render('update', [
            'article'=>$article,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $article = $model->info;
        if ($model->load(Yii::$app->request->post()) && $article->load(Yii::$app->request->post())) {

            $model->level=-1;
            if($model->save())
            {

                $article->id=$model->id;
                $imagesFile = UploadedFile::getInstancesByName(Html::getInputName($article,'img'));
                if($imagesFile) {
                    $upload= new UploadForm();
                    $upload->imageFiles = $imagesFile;
                    $image = $upload->upload();
                    $article->img = $image[0];
                }else{
                    $article->img = ArticleInfo::findOne($model->id)->img;
                }
                if($article->save()) {
                    \Yii::$app->getSession()->setFlash('success');

                    return $this->redirect(['update', 'id' => $model->id]);
                }
            }
            \Yii::$app->getSession()->setFlash('error', implode(',',$model->firstErrors).implode(',',$article->firstErrors));
        }
        return $this->render('update', [
            'article'=>$article,
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Article model.
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
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPush()
    {
        $model=new Push();
        $post=Yii::$app->request->post();
        $model->id          =$post['Push']['id'];
        $model->hospital    =$post['Push']['hospital'];
        $model->age         =$post['Push']['age'];
        $model->area        =$post['Push']['area'];
        $model->test        =$post['Push']['test'];


        $model->send();

        \Yii::$app->getSession()->setFlash('success','发送成功');

        return $this->redirect(Yii::$app->request->referrer);
    }
}
