<?php

namespace hospital\controllers;

use common\helpers\SmsSend;
use common\helpers\WechatSendTmp;
use common\models\ChildInfo;
use common\models\Log;
use common\models\UserDoctor;
use common\models\UserLogin;
use docapi\models\AppointSearch;
use hospital\models\user\Hospital;
use Yii;
use common\models\Appoint;
use hospital\models\AppointSearchModels;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AppointController implements the CRUD actions for Appoint model.
 */
class AppointController extends BaseController
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

    public function actionDown()
    {
        ini_set('memory_limit', '2048M');
        ini_set("max_execution_time", "0");
        set_time_limit(0);


        $params = \Yii::$app->request->queryParams;
        $searchModel = new AppointSearchModels();
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
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $key1, '姓名')
            ->setCellValue('B' . $key1, '手机号')
            ->setCellValue('C' . $key1, '预约项目')
            ->setCellValue('D' . $key1, '预约人其他信息')
            ->setCellValue('E' . $key1, '预约日期')
            ->setCellValue('F' . $key1, '预约时间')
            ->setCellValue('G' . $key1, '预约状态')
            ->setCellValue('H' . $key1, '取消原因')
            ->setCellValue('I' . $key1, '来源')
            ->setCellValue('J' . $key1, '预约疫苗')
            ->setCellValue('K' . $key1, '排号顺序');
//写入内容
        foreach ($dataProvider->query->limit(500)->all() as $k => $e) {
            $v = $e->toArray();
            $child = \common\models\ChildInfo::findOne(['id' => $v['childid']]);
            $userParent = \common\models\UserParent::findOne(['userid' => $v['userid']]);

            $index = \common\models\Appoint::find()
                ->andWhere(['appoint_date' => $e->appoint_date])
                ->andWhere(['<', 'id', $e->id])
                ->andWhere(['doctorid' => $e->doctorid])
                ->andWhere(['appoint_time' => $e->appoint_time])
                ->andWhere(['type' => $e->type])
                ->count();

            $vaccine=$e->vaccine?\common\models\Vaccine::findOne($e->vaccine)->name:"";



            if($e->type==4){
                $name= \common\models\AppointAdult::findOne(['userid' => $e->userid])->name;

            }elseif($e->type==5 || $e->type==6){
                $name= \common\models\Pregnancy::findOne(['id' => $e->childid])->field1;
            }else{
                $name= \common\models\ChildInfo::findOne(['id' => $e->childid])->name;
            }
            if($e->type==4){
                $row=\common\models\AppointAdult::findOne(['userid' => $e->userid]);
                $html="姓名：".$row->name."<br>";
                $html.="性别：".\common\models\AppointAdult::$genderText[$row->gender]."<br>";
            }elseif($e->type==5 || $e->type==6){
                $preg=\common\models\Pregnancy::findOne(['id' => $e->childid]);
                $html="末次月经：".date('Ymd',$preg->field11)."<br>";
                $html.="证件号：".$preg->field4."<br>";
                $html.="户籍地：".\common\models\Pregnancy::$field90[$preg->field90]."<br>";
                $html.="孕妇户籍地：".\common\models\Area::$all[$preg->field7]."<br>";
                $html.="丈夫户籍地：".\common\models\Area::$all[$preg->field39]."<br>";
                $html.="现住址：".$preg->field10."<br>";
            }else{
                $child= \common\models\ChildInfo::findOne(['id' => $e->childid]);
                $parent= \common\models\UserParent::findOne(['userid' => $e->userid]);
                $html="性别：".$child->name."<br>";
                $html.="生日：".date('Ymd',$child->birthday)."<br>";
                $html.="儿童户籍：".$child->fieldu47."<br>";
                $html.="母亲姓名：".$parent->mother."<br>";
                $html.="户籍地：".$parent->field44."<br>";
            }

            $key1 = $k + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $key1, $name)
                ->setCellValue('B' . $key1, $e->phone)
                ->setCellValue('C' . $key1, \common\models\Appoint::$typeText[$e->type])
                ->setCellValue('D' . $key1, $html)
                ->setCellValue('E' . $key1, date('Y-m-d', $v['appoint_date']))
                ->setCellValue('F' . $key1, \common\models\Appoint::$timeText[$v['appoint_time']])
                ->setCellValue('G' . $key1, \common\models\Appoint::$stateText[$e->state])
                ->setCellValue('H' . $key1, \common\models\Appoint::$cancel_typeText[$e->cancel_type])
                ->setCellValue('I' . $key1, \common\models\Appoint::$modeText[$e->mode])
                ->setCellValue('J' . $key1, $e->vaccine==-2?"两癌筛查":$vaccine)
                ->setCellValue('K' . $key1, $e->appoint_time . "-" . ($index + 1));

        }
        // $objPHPExcel->setActiveSheetIndex(0);

        ob_end_clean();
        ob_start();

        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="预约列表-' . date("Y年m月j日") . '.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function actionDone($id)
    {

        $model = $this->findModel($id);
        $model->state = 2;
        if ($model->save()) {

            $login = UserLogin::findOne(['id' => $model->loginid]);
            $data = [
                'first' => ['value' => '服务已完成'],
                'keyword1' => ARRAY('value' => Appoint::$typeText[$model->type]),
                'keyword2' => ARRAY('value' => date('Y年m月d日 H:i:00')),
                'remark' => ARRAY('value' => "感谢您对社区医院本次服务的支持，如有问题请联系在线客服"),

            ];
            $rs = WechatSendTmp::send($data, $login->openid, 'oxn692SYkr2EIGlVIhYbS1C4Qd6FpmeYLbsFtyX45CA', '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => '/pages/appoint/my?type=2',]);

        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPush($childid)
    {
        $model = new \common\models\Appoint();
        if ($post = Yii::$app->request->post()) {
            $model->load($post);
            $doctor = UserDoctor::findOne(['hospitalid' => Yii::$app->user->identity->hospital]);
            $model->appoint_date = strtotime($model->date);
            $model->doctorid = $doctor->userid;
            $model->state = 5;
            $model->push_state = 1;
            $model->mode = 1;
            $log = new \common\components\Log('Appoint_Doctor_Push');
            if ($model->save()) {
                if ($model->loginid) {
                    $login = UserLogin::findOne(['id' => $model->loginid]);
                    if ($login->openid) {
                        $log->addLog("微信");

                        if ($model->type == 1) {
                            $data = [
                                'first' => ['value' => "家长您好，该带宝宝来社区服务中心体检啦\n\n" . "预约时间：" . $model->date . " " . Appoint::$timeText[$model->appoint_time]],
                                'keyword1' => ARRAY('value' => $doctor->name),
                                'keyword2' => ARRAY('value' => $doctor->phone),
                                'keyword3' => ARRAY('value' => $model->remark),
                                'remark' => ARRAY('value' => "\n需要点击此消息并确定领取凭证，到社区服务中心出示此凭证即可享受服务，为了宝宝的健康，请安排好您的时间哦"),
                            ];
                        } elseif ($model->type == 2) {
                            $data = [
                                'first' => ['value' => "家长您好，该带宝宝来社区服务中心接种啦\n\n" . "预约时间：" . $model->date . " " . Appoint::$timeText[$model->appoint_time]],
                                'keyword1' => ARRAY('value' => $doctor->name),
                                'keyword2' => ARRAY('value' => $doctor->phone),
                                'keyword3' => ARRAY('value' => $model->remark),
                                'remark' => ARRAY('value' => "\n需要点击此消息并确定领取凭证，到社区服务中心出示此凭证即可享受服务，为了宝宝的健康，请安排好您的时间哦"),
                            ];
                        }
                        $log->addLog($login->openid);

                        $rs = WechatSendTmp::send($data, $login->openid, '3ui_xwyZXEw4DK4Of5FRavHDziSw3kiUyeo74-B0grk', '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => '/pages/appoint/my?type=1',]);
                        $log->addLog($rs);
                        if ($rs) {
                            $model->push_state = 2;
                        } else {
                            $model->push_state = 4;
                        }
                        $model->save();
                    }
                }
                if (!$login->openid || $rs == false) {
                    $log->addLog("短信");

                    $data['doctor'] = $doctor->name;
                    $data['phone'] = $doctor->phone;
                    $data['type'] = Appoint::$typeText1[$model->type];
                    $data['date_time'] = $model->date . " " . explode('-', Appoint::$timeText[$model->appoint_time])[0];
                    //$data['time'] = Appoint::$timeText[$model->appoint_time];
                    $rs = SmsSend::appoint($data, $model->phone);
                    $log->addLog($rs ? 'true' : 'false');
                    if ($rs) {
                        $model->push_state = 3;
                    } else {
                        $model->push_state = 5;
                    }
                    $model->save();
                }
                $log->saveLog();
                return $this->redirect(['index']);
            }
        }


        return $this->render('push', [
            'childid' => $childid,
            'model' => $model,
        ]);
    }

    /**
     * Lists all Appoint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppointSearchModels();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Appoint model.
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
     * Creates a new Appoint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Appoint();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Appoint model.
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
     * Deletes an existing Appoint model.
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
     * Finds the Appoint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Appoint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Appoint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
