<?php

namespace hospital\controllers;

use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\UserDoctor;
use Yii;
use common\models\DoctorTeam;
use hospital\models\DoctorTeamSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use udokmeci\yii2beanstalk\BeanstalkController;

/**
 * DoctorTeamController implements the CRUD actions for DoctorTeam model.
 */
class DoctorTeamController extends Controller
{
    /**
     * @inheritdocÒ
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
     * Lists all DoctorTeam models.
     * @return mixed
     */
    public function actionIndex()
    {

        $userDoctor = UserDoctor::findOne(['userid' => \Yii::$app->user->identity->doctorid]);
        if (isset($_GET['is_team'])) {
            $userDoctor->is_team = $_GET['is_team'];
            $userDoctor->save();
            return;
        }

        $searchModel = new DoctorTeamSearch();
        $params = Yii::$app->request->queryParams;
        $params['DoctorTeamSearch']['doctorid'] = Yii::$app->user->identity->doctorid;
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'userDoctor' => $userDoctor,

        ]);
    }

    /**
     * Displays a single DoctorTeam model.
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
     * Creates a new DoctorTeam model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DoctorTeam();
        $model->doctorid = Yii::$app->user->identity->doctorid;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DoctorTeam model.
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
     * Deletes an existing DoctorTeam model.
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
     * Finds the DoctorTeam model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DoctorTeam the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DoctorTeam::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionData()
    {
        $teams = DoctorTeam::findAll(['doctorid' => Yii::$app->user->identity->doctorid]);
        return $this->render('data', ['teams' => $teams, 'doctorid' => Yii::$app->user->identity->doctorid]);
    }

    public function actionDataEx()
    {

        if($_FILES['team-file']['tmp_name']) {
            $objRead = new Xlsx();   //建立reader对象
            $objRead->setReadDataOnly(true);
            $obj = $objRead->load($_FILES['team-file']['tmp_name']);  //建立excel对象
            $currSheet = $obj->getSheet(0);   //获取指定的sheet表
            $columnH = $currSheet->getHighestColumn();   //取得最大的列号
            $highestColumnNum = Coordinate::columnIndexFromString($columnH);
            $rowCnt = $currSheet->getHighestRow();   //获取总行数
            $sheetData = $currSheet->toArray(null, true, true, true);

            $teamid = $_POST['teamid'];
            $i=0;
            if ($teamid) {
                foreach ($sheetData as $k => $v) {
                    $child = ChildInfo::find()
//                ->select('user_login.phone')
//                ->leftJoin('user_login', '`user_login`.`userid` = `child_info`.`userid`')
//                ->andWhere(['`user_login`.`phone`' => $phone])
                        ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                        ->andWhere(['doctor_parent.doctorid' => Yii::$app->user->identity->doctorid])
                        ->leftJoin('user_parent', '`user_parent`.`userid` = `child_info`.`userid`')
                        ->andWhere(['user_parent.mother' => $v[2]])
//                ->orWhere(['pregnancy.field4'=>$phone])
                        ->andWhere(['child_info.name' => $v[0]])
                        ->andWhere(['child_info.birthday' => strtotime($v[1])])
                        ->one();
                    if ($child) {
                        $doctorParent = DoctorParent::findOne(['parentid' => $child->userid]);
                        $doctorParent->teamid = $teamid;
                        $doctorParent->save();
                        $i++;
                    }

                }
                if($i<1){
                    \Yii::$app->getSession()->setFlash('success', '未查询到儿童请检查上传文件是否正确，需要字段有："儿童姓名，出生日期，母亲姓名"');
                }else {
                    \Yii::$app->getSession()->setFlash('success', '处理完成共匹配成功"' . $i . '"个儿童');
                }

            } else {
                \Yii::$app->getSession()->setFlash('error', '未选择家医团队');
            }
        }else{
            \Yii::$app->getSession()->setFlash('error', '请上传家医团队文件');
        }
        return $this->redirect(['data']);

    }
}

