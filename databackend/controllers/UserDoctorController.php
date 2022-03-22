<?php

namespace databackend\controllers;

use app\components\UploadForm;
use databackend\models\user\ChildInfoSearchModel;
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
        echo $_SERVER['HTTP_HOST'] . "<br>"; #localhost

        //获取网页地址
        echo $_SERVER['PHP_SELF'] . "<br>"; #/blog/testurl.php

        //获取网址参数
        echo $_SERVER["QUERY_STRING"] . "<br>"; #id=5

        //获取用户代理
        echo $_SERVER['HTTP_REFERER'] . "<br>";

        //获取完整的url
        echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "<br>";
        echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . "<br>";
        #http://localhost/blog/testurl.php?id=5

        //包含端口号的完整url
        echo 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"] . "<br>";
        #http://localhost:80/blog/testurl.php?id=5

        //只取路径
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"] . "<br>";
        echo dirname($url);
        #http://localhost/blog
    }

    /**
     * Lists all UserDoctor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;

        $searchModel = new ChildInfoSearchModel();
        $searchModel->load($params);
        $doctor = UserDoctor::find()->andFilterWhere(['county' => \Yii::$app->user->identity->county])->andWhere(['is_guanfang'=>0])->andFilterWhere(['>', 'userid', 37])->all();

        return $this->render('index', [
            'doctor' => $doctor,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * Lists all UserDoctor models.
     * @return mixed
     */
    public function actionList()
    {
        $params = Yii::$app->request->queryParams;


        $doctor = UserDoctor::find()->andFilterWhere(['county' => \Yii::$app->user->identity->county])->andFilterWhere(['>', 'userid', 37])->all();

        return $this->render('list', [
            'doctor' => $doctor,
        ]);
    }
    /**
     * Lists all UserDoctor models.
     * @return mixed
     */
    public function actionNum()
    {
        $params = Yii::$app->request->queryParams;


        $doctor = UserDoctor::find()->andFilterWhere(['county' => \Yii::$app->user->identity->county])->andFilterWhere(['>', 'userid', 37])->all();

        return $this->render('num', [
            'doctor' => $doctor,
        ]);
    }
    /**
     * Lists all UserDoctor models.
     * @return mixed
     */
    public function actionForm()
    {
        $params = Yii::$app->request->queryParams;

        $doctor = UserDoctor::find()->select('userid')->andFilterWhere(['county' => \Yii::$app->user->identity->county])->andFilterWhere(['>', 'userid', 37])->column();
        $sdate=Yii::$app->request->post('sdate');
        $edate=Yii::$app->request->post('edate');
        $query=\common\models\HospitalForm::find()
            ->select('sum(sign3) as e,sum(sign1) as a,sum(sign2) as b,sum(appoint_num) as c,sum(other_appoint_num) as d,doctorid')
            ->where(['in','doctorid',$doctor]);
        if($sdate && $edate){
            $query->andWhere(['>=','date',strtotime($sdate)])->andWhere(['<=','date',strtotime($edate)]);
        }
        $doctors=$query->groupBy('doctorid')->asArray()->all();
        return $this->render('form', [
            'doctors' => $doctors,
        ]);
    }

    /**
     * Lists all UserDoctor models.
     * @return mixed
     */
    public function actionDown()
    {
        $params = Yii::$app->request->queryParams;

        $searchModel = new ChildInfoSearchModel();
        $searchModel->load($params);
        $doctor = UserDoctor::find()->andFilterWhere(['county' => \Yii::$app->user->identity->county])->andFilterWhere(['>', 'userid', 37])->all();


        ini_set('memory_limit', '2048M');
        ini_set("max_execution_time", "0");
        set_time_limit(0);


        $params = \Yii::$app->request->queryParams;
        $searchModel = new ChildInfoSearchModel();
        $dataProvider = $searchModel->search($params);


        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties();

        //设置A3单元格为文本
        $objPHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $objPHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $objPHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $objPHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $key1 = 1;
        //社区卫生服务中心	辖区内管理儿童数	今日签约	签约总数	今日宣教	已宣教数	管理服务率	数据	宣教记录
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $key1, '社区卫生服务中心')
            ->setCellValue('B' . $key1, '辖区内管理儿童数')
            ->setCellValue('C' . $key1, '今日签约')
            ->setCellValue('D' . $key1, '签约总数')
            ->setCellValue('E' . $key1, '今日宣教')
            ->setCellValue('F' . $key1, '已宣教数')
            ->setCellValue('G' . $key1, '签约率');

//写入内容
        foreach ($doctor as $k => $v) {

            $total = \common\models\ChildInfo::find()
                ->andFilterWhere(['source' => $v->hospitalid])
                ->andFilterWhere(['admin' => $v->hospitalid])
                ->andFilterWhere(['>', 'birthday', strtotime('-3 year')])
                ->count();
            $today = strtotime(date('Y-m-d 00:00:00'));
            //今日已签约
            $todayNum = \common\models\ChildInfo::find()
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                ->andFilterWhere(['`doctor_parent`.`doctorid`' => $v->userid])
                ->andFilterWhere(['child_info.admin' => $v->hospitalid])
                ->andFilterWhere([">", '`doctor_parent`.createtime', $today])
                ->andFilterWhere(['>', 'child_info.birthday', strtotime('-3 year')])
                ->count();

            //已签约总数
            $childInfoQuery = \common\models\ChildInfo::find()
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                ->andFilterWhere(['`doctor_parent`.doctorid' => $v->userid])
                ->andFilterWhere(['>', 'child_info.birthday', strtotime('-3 year')])
                ->andFilterWhere(['child_info.admin' => $v->hospitalid])
                ->andFilterWhere(['`doctor_parent`.level' => 1]);
            if ($searchModel->docpartimeS !== '' and $searchModel->docpartimeS !== null) {
                $state = strtotime($searchModel->docpartimeS . " 00:00:00");
                $end = strtotime($searchModel->docpartimeE . " 23:59:59");
                $childInfoQuery->andFilterWhere(['>', '`doctor_parent`.`createtime`', $state]);
                $childInfoQuery->andFilterWhere(['<', '`doctor_parent`.`createtime`', $end]);
            }
            $q = $childInfoQuery->count();
            $todayXnum = \common\models\ArticleUser::find()
                ->andFilterWhere(['userid' => $v->userid])
                ->andFilterWhere([">", 'createtime', $today])->count();
            //已宣教
            $Xnum = \common\models\ArticleUser::find()
                ->andFilterWhere(['userid' => $v->userid])->count();
            if ($total) {
                $baifen = round(($q / $total) * 100, 1);
            } else {
                $baifen = 0;
            }

            $key1 = $k + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $key1, $v->name)
                ->setCellValue('B' . $key1, $total)
                ->setCellValue('C' . $key1, $todayNum)
                ->setCellValue('D' . $key1, $q)
                ->setCellValue('E' . $key1, $todayXnum)
                ->setCellValue('F' . $key1, $Xnum)
                ->setCellValue('G' . $key1, $baifen);
        }
        // $objPHPExcel->setActiveSheetIndex(0);

        ob_end_clean();
        ob_start();

        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="社区管辖签约情况-' . date("Y年m月j日") . '.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
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
        $userInfo = new \databackend\models\user\UserDoctor();
        $userLogin = new UserLogin();
        $model->loadDefaultValues();
        $userInfo->loadDefaultValues();
        $userLogin->loadDefaultValues();
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                $userInfo->load(Yii::$app->request->post());

                $imagesFile = UploadedFile::getInstancesByName(Html::getInputName($userInfo, 'avatar'));
                if ($imagesFile) {
                    $upload = new UploadForm();
                    $upload->imageFiles = $imagesFile;
                    $image = $upload->upload();
                    $userInfo->avatar = $image[0];
                }
                $userInfo->userid = $model->id;
                if ($userInfo->save()) {

                    $userLogin->userid = $model->id;
                    $userLogin->password = md5(md5($model->phone . "2QH@6%3(87"));
                    $userLogin->save();
                    return $this->redirect(['update', 'id' => $model->id]);

                }
            }
            \Yii::$app->getSession()->setFlash('error', 'userinfo:' . implode(',', $userInfo->firstErrors) . 'userLogin:' . implode(',', $userLogin->firstErrors) . 'model:' . implode(',', $model->firstErrors));
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
        $userInfo = \databackend\models\user\UserDoctor::findOne(['userid' => $id]);
        $userInfo = $userInfo ? $userInfo : new \databackend\models\user\UserDoctor();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $userid = $model->save();
            $userInfo->load(Yii::$app->request->post());

            $imagesFile = UploadedFile::getInstancesByName(Html::getInputName($userInfo, 'avatar'));
            if ($imagesFile) {
                $upload = new UploadForm();
                $upload->imageFiles = $imagesFile;
                $image = $upload->upload();
                $userInfo->avatar = $image[0];
            } else {
                $userInfo->avatar = UserDoctor::findOne(['userid' => $id])->avatar;
            }
            $userInfo->userid = $model->id;
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
