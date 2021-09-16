<?php

namespace hospital\controllers;

use common\models\QuestionNaireAsk;
use Yii;
use common\models\QuestionNaireField;
use hospital\models\QuestionNaireFieldSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * QuestionNaireFieldController implements the CRUD actions for QuestionNaireField model.
 */
class QuestionNaireFieldController extends Controller
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
     * Lists all QuestionNaireField models.
     * @return mixed
     */
    public function actionIndex($qnid=4)
    {
        $searchModel = new QuestionNaireFieldSearch();
        $searchModel->qnid=$qnid;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'qnid'=>$qnid,
        ]);
    }
    public function actionDown($qnid=4){
        $searchModel = new QuestionNaireFieldSearch();
        $searchModel->qnid=$qnid;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination=false;
        $data=$dataProvider->query->all();
        foreach ($data as $k=>$v){
            $qna=\common\models\QuestionNaireAnswer::find()->where(['qnfid'=>$v->id])->select('answer')->indexBy('qnaid')->column();
            if(!$k) {
                $keys = QuestionNaireAsk::find()->where(['id' => array_keys($qna)])->select('content')->orderBy(["FIELD(id, " . join(',', array_keys($qna)) . ")" => true])->column();
            }
            $rs[]=$qna;
        }
        $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'sheets' => [
                'Users' => [
                    'data' => $rs,
                    'titles' => $keys,
                ]
            ]
        ]);
        $file->send('筛查表.xlsx');
    }

    /**
     * Displays a single QuestionNaireField model.
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
     * Creates a new QuestionNaireField model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new QuestionNaireField();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing QuestionNaireField model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing QuestionNaireField model.
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
     * Finds the QuestionNaireField model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return QuestionNaireField the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QuestionNaireField::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
