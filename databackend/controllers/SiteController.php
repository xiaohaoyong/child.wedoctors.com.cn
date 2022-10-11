<?php
namespace databackend\controllers;

use common\models\ArticleUser;
use common\models\Autograph;
use common\models\ChildInfo;
use common\models\Pregnancy;
use common\models\UserDoctor;
use databackend\models\article\Article;
use databackend\models\user\DoctorParent;
use Yii;
use common\models\LoginForm;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends BaseController
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 50,
                'width' => 100,
                'maxLength' => 4,
                'minLength' => 4
            ],
        ];
    }
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['logout','index','login', 'error', 'captcha','data'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $hospitalid=ArrayHelper::getColumn($this->doctor,'hospitalid');
        $doctorid=ArrayHelper::getColumn($this->doctor,'userid');

        $today=strtotime(date('Y-m-d 00:00:00'));

        //今日签约数
        $data['todayNum']=ChildInfo::find()
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])
            ->andFilterWhere(['child_info.admin'=>$hospitalid])
            ->andFilterWhere([">",'`doctor_parent`.createtime',$today])
            ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
            ->count();

        //今日签字数
        $data['todayInkNum']=Autograph::find()
            ->andFilterWhere(['doctorid'=>$doctorid])
            ->andFilterWhere([">",'createtime',$today])
            ->count();

        //签约儿童总数
        $data['todayNumTotal']=ChildInfo::find()
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])
            ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-3 year')])
            ->andFilterWhere(['child_info.admin'=>$hospitalid])
            ->count();


        //管辖儿童数（0-3）
        $data['childNum']=ChildInfo::find()
            ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
            ->andFilterWhere(['`child_info`.`source`' => $hospitalid])
            ->andFilterWhere(['`child_info`.admin'=>$hospitalid])
            ->count();

        //管辖儿童数（0-6）
        $adminsix=ChildInfo::find()
            ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-6 year')])
            ->andFilterWhere(['`child_info`.admin'=>$hospitalid])
            ->count();

        $nadminsix=ChildInfo::find()
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])
            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
            ->andWhere(['!=','`child_info`.`admin`' ,$hospitalid])
            ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-6 year')])
            ->count();

        $data['achildNum']=$adminsix+$nadminsix;
        //var_dump($hospitalid);exit;
        //签约率
        if($data['childNum']) {
            $data['baifen'] = round(($data['todayNumTotal'] / $data['childNum']) * 100,1);
        }else{
            $data['baifen'] = 0;
        }




        $auto=Autograph::find()->select('userid')->where(['doctorid'=>$doctorid])->column();
        if($auto) {
            $data['AutoNum'] = ChildInfo::find()
                ->andFilterWhere(['in', '`child_info`.`userid`', array_unique($auto)])
                ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-6 year')])
                ->count();
        }else{
            $data['AutoNum']=0;
        }

        //签约率
        if($data['AutoNum'] && $data['achildNum']) {
            $data['abaifen'] = round(($data['AutoNum'] / $data['achildNum']) * 100,1);
        }else{
            $data['abaifen'] = 0;
        }

        //宣教总次数
        $data['articleNum']=ArticleUser::find()
            ->andFilterWhere(['article_user.userid'=>$doctorid])
            ->count();

        $data['articleNumToday']=ArticleUser::find()
            ->andFilterWhere(['>','createtime',$today])
            ->andFilterWhere(['article_user.userid'=>$doctorid])
            ->count('distinct childid');


        $doctorParent= \common\models\DoctorParent::find()->select('parentid')->where(['doctorid'=>$doctorid])->andFilterWhere(['level'=>1])->column();

        //该类型 本月已发送的儿童
        $articleUser=ArticleUser::find()->select('touserid')
            ->where(['userid'=>$doctorid])
            //->andFilterWhere(['>','createtime',strtotime($lmount)])
            ->groupBy('childid')
            ->column();

        $users=array_diff($doctorParent,$articleUser);

        if($users) {
            foreach (\common\models\Article::$childText as $k => $v) {
                if ($k) {
                    $mouth[] = ChildInfo::getChildTypeDay($k);
                }
            }
            $childCount = ChildInfo::find()->andFilterWhere(['in', 'birthday', $mouth])->andFilterWhere(['in', 'userid', array_values($users)])->count();
        }else{
            $childCount=0;
        }
        $data['articleNoMonth']=$childCount;

        for($i=9;$i>-1;$i--)
        {
            $time=strtotime("-$i day");
            $date=date('Y-m-d',$time);
            $st=strtotime($date."00:00:00");
            $end=$st+3600*24;
            $rs['item1']=ChildInfo::find()
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                ->andFilterWhere(['>','`doctor_parent`.createtime',$st])
                ->andFilterWhere(['<','`doctor_parent`.createtime',$end])
                ->andFilterWhere(['doctor_parent.doctorid'=>$doctorid])
                ->count();

            $rs['day']=$date;
            $line_data[]=$rs;
        }

        $now=ChildInfo::find()
            ->leftJoin('user','`user`.`id`=`child_info`.`userid`')
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`'=>1])
            ->andFilterWhere(['doctor_parent.doctorid'=>$doctorid])
            ->andFilterWhere(['child_info.admin'=>$hospitalid])
            ->orderBy('`doctor_parent`.`createtime` desc')->limit(9)->all();



        $data['pregCount']=Pregnancy::find()
            ->andWhere(['field49'=>0])->andWhere(['>','field11',strtotime('-43 week')])->andWhere(['doctorid'=>$hospitalid])->count();

        $data['pregLCount']=Pregnancy::find()
            ->andWhere(['pregnancy.field49'=>0])
            ->andWhere(['>','pregnancy.field11',strtotime('-43 week')])
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `pregnancy`.`familyid`')
            ->andWhere(['pregnancy.doctorid'=>$hospitalid])
            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])->count();

        $data['todayPregLCount']=Pregnancy::find()
            ->andWhere(['pregnancy.field49'=>0])
            ->andWhere(['>', 'pregnancy.familyid', 0])
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `pregnancy`.`familyid`')
            ->andWhere(['>','doctor_parent.createtime',strtotime(date('Y-m-d'))])
            ->andWhere(['<','doctor_parent.createtime',strtotime(date('Y-m-d 23:59:59'))])

            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])->count();

        return $this->render('index',[
            'data'=>$data,
            'line_data'=>$line_data,
            'now'=>$now,
        ]);
    }



    public function actionData(){
        ini_set('memory_limit', '2048M');
        ini_set("max_execution_time", "0");
        set_time_limit(0);

        $doctor=UserDoctor::find()->andFilterWhere(['county'=>\Yii::$app->user->identity->county])->andFilterWhere(['>','userid',37])->all();



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
            ->setCellValue('A'.$key1, '社区卫生服务中心')
            ->setCellValue('B'.$key1, '辖区内管理儿童数')
            ->setCellValue('C'.$key1, '今日签约')
            ->setCellValue('D'.$key1, '签约总数')
            ->setCellValue('E'.$key1, '今日宣教')
            ->setCellValue('F'.$key1, '已宣教数')
            ->setCellValue('G'.$key1, '数据');
//写入内容
        $today = strtotime(date('Y-m-d 00:00:00'));

        foreach($doctor as $k=>$v) {

            //今日已签约
            $todayNum= \common\models\ChildInfo::find()
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                ->andFilterWhere(['`doctor_parent`.`doctorid`' => $v->userid])
                ->andFilterWhere(['child_info.admin'=>$v->hospitalid])
                ->andFilterWhere([">",'`doctor_parent`.createtime',$today])
                ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
                ->count();
            //已签约总数
            $q = \common\models\ChildInfo::find()
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                ->andFilterWhere(['`doctor_parent`.doctorid' => $v->userid])
                ->andFilterWhere(['>', 'child_info.birthday', strtotime('-3 year')])
                ->andFilterWhere(['child_info.admin' => $v->hospitalid])
                ->andFilterWhere(['`doctor_parent`.level' => 1])->count();
            $today = strtotime(date('Y-m-d 00:00:00'));
            //今日宣教
            $anum= \common\models\ArticleUser::find()
                ->andFilterWhere(['userid' => $v->userid])
                ->andFilterWhere([">", 'createtime', $today])->count();
            $bnum=\common\models\ArticleUser::find()
                ->andFilterWhere(['userid' => $v->userid])->count();
            $total=\common\models\ChildInfo::find()->andFilterWhere(['source' => $v->hospitalid])->andFilterWhere(['admin' => $v->hospitalid])->andFilterWhere(['>', 'birthday', strtotime('-3 year')])->count();
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
                ->setCellValue('E' . $key1, $anum)
                ->setCellValue('F' . $key1, $bnum)
                ->setCellValue('G' . $key1, $baifen."%");
        }
        // $objPHPExcel->setActiveSheetIndex(0);

        ob_end_clean();
        ob_start();

        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.date("Y年m月j日").'社区统计数据.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }


    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {     //①
            return $this->goHome();
        }

        $model = new \databackend\models\LoginForm();             //②
        if ($model->load(Yii::$app->request->post()) && $model->login()) {      //③
            return $this->goBack();          //④
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
