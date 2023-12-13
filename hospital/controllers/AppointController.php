<?php

namespace hospital\controllers;

use common\helpers\SmsSend;
use common\helpers\WechatSendTmp;
use common\models\AppointOrder2;
use common\models\ChildInfo;
use common\models\Log;
use common\models\UserDoctor;
use common\models\UserLogin;
use docapi\models\AppointSearch;
use hospital\models\user\Hospital;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use common\models\Appoint;
use common\models\Vaccine;
use hospital\models\AppointSearchModels;
use PHPExcel;
use PHPExcel_Style;
use PHPExcel_Style_NumberFormat;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
/**
 * AppointController implements the CRUD actions for Appoint model.
 */
class AppointController extends BaseController
{


    public function actionDown()
    {
        ini_set('memory_limit', '2048M');
        ini_set("max_execution_time", "0");
        set_time_limit(0);


        $params = \Yii::$app->request->queryParams;
        $searchModel = new AppointSearchModels();
        $dataProvider = $searchModel->search($params);
        //require('/Users/wangzhen/PhpstormProjects/child.wedoctors.com.cn/vendor/phpoffice/phpexcel/Classes/PHPExcel.php');

        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->getProperties();

        //设置A3单元格为文本
        $objPHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
        $objPHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
        $objPHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
        $objPHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
        $key1 = 1;
        if($searchModel->type==11){
            $fields = ['患者姓名', '身份证号', '性别', '医保类型', '诊断名称', '病程（年）', '家族史', '联系电话', '已贴敷年数', '去年发病次数', '去年门诊和急诊次数', '去年住院次数', '去年发病总天数', '疗效评价', '不良反应'];
            $model=$objPHPExcel->setActiveSheetIndex(0);
            foreach($fields as $k=>$v){
                $key=65+$k;
                $model->setCellValue(chr($key) . $key1, $v);
            }


        }elseif($searchModel->type!=7 and $searchModel->type!=4 and $searchModel->type!=9) {
            $fields = ['姓名', '性别', '生日', '儿童户籍', '母亲姓名', '户籍地', '预约日期', '预约时间', '手机号', '预约状态', '预约项目', '选择疫苗', '取消原因', '推送状态', '来源', '排号顺序','备注'];
            $model=$objPHPExcel->setActiveSheetIndex(0);
            foreach($fields as $k=>$v){
                $key=65+$k;
                $model->setCellValue(chr($key) . $key1, $v);
            }
        }else{
            $fields = ['姓名', '性别', '生日', '身份证号', '联系电话', '户籍地', '预约日期', '预约时间', '预约状态', '预约项目', '取消原因', '推送状态', '来源','备注','预约社区','预约疫苗'];
            $model=$objPHPExcel->setActiveSheetIndex(0);
            foreach($fields as $k=>$v){
                $key=65+$k;
                $model->setCellValue(chr($key) . $key1, $v);
            }
            $objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
        }

//        $objPHPExcel=$objPHPExcel->setActiveSheetIndex(0);
//        foreach($fields as $k=>$v){
//            $objPHPExcel->setCellValue(chr(65+$k) . $key1, $v);
//        }



//写入内容
        foreach ($dataProvider->query->all() as $k => $e) {
            $v = $e->toArray();
            $key1 = $k + 2;
            if($searchModel->type==11){
                if($e->childid){
                    $row= \common\models\AppointAdult::findOne(['id' => $v['childid']]);
                }else {
                    $row= \common\models\AppointAdult::findOne(['userid' => $v['userid']]);
                }
                $appointOrder=AppointOrder2::findOne(['aoid'=>$row->id]);
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $key1, $row->name)
                    ->setCellValue('B' . $key1, $row->id_card."  ")
                    ->setCellValue('C' . $key1, \common\models\AppointAdult::$genderText[$row->gender])
                    ->setCellValue('D' . $key1, AppointOrder2::$typeText[$appointOrder->type])
                    ->setCellValue('E' . $key1, AppointOrder2::$zhenduanText[$appointOrder->zhenduan])
                    ->setCellValue('F' . $key1, $appointOrder->bingcheng)
                    ->setCellValue('G' . $key1, $appointOrder->jiazushu)
                    ->setCellValue('H' . $key1, $e->phone)
                    ->setCellValue('I' . $key1, $appointOrder->field1)
                    ->setCellValue('J' . $key1, $appointOrder->field2)
                    ->setCellValue('K' . $key1, $appointOrder->field3)
                    ->setCellValue('L' . $key1, $appointOrder->field4)
                    ->setCellValue('M' . $key1, $appointOrder->field5)
                    ->setCellValue('N' . $key1, AppointOrder2::$field6Text[$appointOrder->field6])
                    ->setCellValue('O' . $key1, $appointOrder->field7)
                ;

            }elseif($searchModel->type!=7 and $searchModel->type!=4 and $searchModel->type!=9) {
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
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $key1, $child->name)
                    ->setCellValue('B' . $key1, \common\models\ChildInfo::$genderText[$child->gender])
                    ->setCellValue('C' . $key1, date('Y-m-d', $child->birthday))
                    ->setCellValue('D' . $key1, $child->fieldu47)
                    ->setCellValue('E' . $key1, $userParent->mother)
                    ->setCellValue('F' . $key1, $userParent->field44)
                    ->setCellValue('G' . $key1, date('Y-m-d', $v['appoint_date']))
                    ->setCellValue('H' . $key1, \common\models\Appoint::$timeText[$v['appoint_time']])
                    ->setCellValue('I' . $key1, $e->phone)
                    ->setCellValue('J' . $key1, \common\models\Appoint::$stateText[$e->state])
                    ->setCellValue('K' . $key1, \common\models\Appoint::$typeText[$e->type])
                    ->setCellValue('L' . $key1, $e->vaccine == -2 ? "两癌筛查" : \common\models\Vaccine::findOne($e->vaccine)->name)
                    ->setCellValue('M' . $key1, \common\models\Appoint::$cancel_typeText[$e->cancel_type])
                    ->setCellValue('N' . $key1, \common\models\Appoint::$push_stateText[$e->push_state])
                    ->setCellValue('O' . $key1, \common\models\Appoint::$modeText[$e->mode])
                    ->setCellValue('P' . $key1, $e->appoint_time . "-" . ($index + 1))
                    ->setCellValue('Q' . $key1, $v['remark']);

            }else{

                if($e->childid){
                    $row= \common\models\AppointAdult::findOne(['id' => $v['childid']]);
                }else {
                    $row= \common\models\AppointAdult::findOne(['userid' => $v['userid']]);
                }

                $doctor=\common\models\UserDoctor::findOne(['userid'=>$e->doctorid]);
                $hospital=\common\models\Hospital::findOne(['id'=>$doctor->hospitalid]);
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $key1, $row->name)
                    ->setCellValue('B' . $key1, \common\models\AppointAdult::$genderText[$row->gender])
                    ->setCellValue('C' . $key1, $row->birthday)
                    ->setCellValue('D' . $key1, $row->place,PHPExcel_Style_NumberFormat::FORMAT_TEXT)
                    ->setCellValue('E' . $key1, $row->phone,DataType::TYPE_STRING)
                    ->setCellValue('F' . $key1, '')
                    ->setCellValue('G' . $key1, date('Y-m-d', $v['appoint_date']))
                    ->setCellValue('H' . $key1, \common\models\Appoint::$timeText[$v['appoint_time']])
                    ->setCellValue('I' . $key1, \common\models\Appoint::$stateText[$e->state])
                    ->setCellValue('J' . $key1, \common\models\Appoint::$typeText[$e->type])
                    ->setCellValue('K' . $key1, \common\models\Appoint::$cancel_typeText[$e->cancel_type])
                    ->setCellValue('L' . $key1, \common\models\Appoint::$push_stateText[$e->push_state])
                    ->setCellValue('M' . $key1, \common\models\Appoint::$modeText[$e->mode])
                    ->setCellValue('N' . $key1, $v['remark'])
                    ->setCellValue('O' . $key1, $hospital->name)
                    ->setCellValue('P' . $key1, Vaccine::findOne($v['vaccine'])->name);



            }

        }
        // $objPHPExcel->setActiveSheetIndex(0);

        ob_end_clean();
        ob_start();

        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="预约列表-' . date("Y年m月j日") . '.xls"');
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');

        $objWriter->save('php://output');
    }



    public function actionDone($id=0)
    {
        $p = Yii::$app->request->queryParams['Appoint'];
        $p = $p?$p:Yii::$app->request->bodyParams['Appoint'];
        $id= $id?$id:$p['id'];
        $state= $p['state']?$p['state']:2;
        $referrer= $p['referrer'];

        $model = $this->findModel($id);
        $userDoctor = UserDoctor::findOne($model->doctorid);

        $hospital = $userDoctor->hospital->name;
        $model->state = $state;
        $model->cancel_type = $p['cancel_type'];
        if ($model->save()) {
            $login = UserLogin::findOne(['id' => $model->loginid]);

            if($state==3){
               $data = [
                   'first' => ['value' => '您的预约已经取消。'],
                   'keyword1' => ARRAY('value' => $model->name()),
                   'keyword2' => ARRAY('value' => Appoint::$typeText[$model->type]),
                   'keyword3' => ARRAY('value' => Appoint::$timeText[$model->appoint_time]),
                   'keyword4' => ARRAY('value' => Appoint::$hospital_cancel[$model->cancel_type]),
               ];
               $tmpid='t-fxuMyA77Xx71OA4_3y528hOSWXk_2rDjvN1zgefbk';
            }elseif($state==2){
                $data = [
                    'first' => ['value' => '服务已完成'],
                    'keyword1' => ARRAY('value' => Appoint::$typeText[$model->type]),
                    'keyword2' => ARRAY('value' => date('Y年m月d日 H:i:00')),
                    'remark' => ARRAY('value' => "感谢您对社区医院本次服务的支持，如有问题请联系在线客服"),

                ];
                $tmpid='oxn692SYkr2EIGlVIhYbS1C4Qd6FpmeYLbsFtyX45CA';
            }elseif($state==1){
                               $data = [
                    'first' => ['value' => ''],
                    'keyword1' => array('value' => Appoint::$typeText[$model->type]),
                    'keyword2' => array('value' => $model->name()),
                    'keyword3' => array('value' => $model->phone),
                    'keyword4' => array('value' => date('Y-m-d',$model->appoint_date)),
                    'keyword5' => array('value' => Appoint::$timeText[$model->appoint_time]),
                    'remark' => array('value' => "尊敬的用户您好，您的预约已生效，请您按照预约时间前往社区，如有问题请联系在线客服"),
                ];
                $tmpid = '83CpoxWB9JCnwdXPr0H7dB66QQnFdJQvBbeMnJ9rdHo';

            }

            $rs = WechatSendTmp::send($data, $login->openid, $tmpid);

        }
        $r=$referrer?$referrer:Yii::$app->request->referrer;
        return $this->redirect($r);
    }


    public function actionDoneAll()
    {
        $params = \Yii::$app->request->queryParams;
        if(!$params['AppointSearchModels']['appoint_dates'] || !$params['AppointSearchModels']['appoint_dates_end'])
        {
            $params['AppointSearchModels']['appoint_date']=strtotime(date('Ymd'));
        }
        if(!$params['AppointSearchModels']['state']){
            return $this->redirect(Yii::$app->request->referrer);
        }
        $searchModel = new AppointSearchModels();
        $dataProvider = $searchModel->search($params);
        foreach($dataProvider->query->all() as $k=>$v){
            $v->state=2;
            if($v->save()){
                $openid=UserLogin::getOpenid($v->userid);
                $data = [
                    'first' => ['value' => '服务已完成'],
                    'keyword1' => ARRAY('value' => Appoint::$typeText[$v->type]),
                    'keyword2' => ARRAY('value' => date('Y年m月d日 H:i:00')),
                    'remark' => ARRAY('value' => "感谢您对社区医院本次服务的支持，如有问题请联系在线客服"),

                ];
                $rs = WechatSendTmp::send($data, $openid, 'oxn692SYkr2EIGlVIhYbS1C4Qd6FpmeYLbsFtyX45CA', '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => '/pages/appoint/my?type=2',]);
            }
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
            if(!$model->vaccine){
                $model->vaccine=0;
            }
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
            'referrer'=>Yii::$app->request->referrer,
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
