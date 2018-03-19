<?php

namespace databackend\controllers;

use app\components\UploadForm;
use common\models\ArticleInfo;
use common\models\Notice;
use databackend\models\user\DoctorParent;
use databackend\models\user\UserDoctor;
use Yii;
use common\models\Article;
use common\models\ArticleSearchModel;
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
        $searchModel = new \databackend\models\article\ArticleSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionTindex()
    {
        Yii::$app->request->queryParams['ArticleSearchModel']['catid']=6;
        $searchModel = new \databackend\models\article\ArticleSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('tindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
    public function actionTongzhi($id=0)
    {
        $model=\databackend\models\article\Article::findOne($id);
        $model=$model?$model:new \databackend\models\article\Article();

        $article=ArticleInfo::findOne($id);
        $article=$article?$article:new ArticleInfo();

        if($article->load(Yii::$app->request->post())){
            $model->catid=6;
            $model->type=6;
            $model->child_type=0;
            if($model->save())
            {
                $article->id=$model->id;

                if($article->save()) {
                    //发送通知
                    $doctorid=UserDoctor::findOne(['hospitalid'=>\Yii::$app->user->identity->hospital])->userid;
                    $doctorParent=DoctorParent::findAll(['doctorid'=>$doctorid]);
                    foreach($doctorParent as $k=>$v)
                    {
                        Notice::setList($v->parentid, 3, ['title' => $article->title, 'ftitle' => $article->ftitle, 'id' => '/article/view/index?id='.$article->id,]);
                    }
                    return $this->redirect(['tindex']);
                }

            }
        }

        if($model->firstErrors){
            \Yii::$app->getSession()->setFlash('error', implode(',',$model->firstErrors).implode(',',$article->firstErrors));

        }
        return $this->render('tongzhi', [
            'article'=>$article,
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new \databackend\models\article\Article();
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
}
