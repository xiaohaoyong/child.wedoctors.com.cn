<?php

namespace backend\controllers;

use backend\models\ChildInfo;
use common\models\ArticleUser;
use common\models\DoctorParent;
use common\models\Pregnancy;
use common\models\UserDoctor;
use common\models\UserParent;
use common\models\WeOpenid;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
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
               //今日签约数
        $today=strtotime(date('Y-m-d 00:00:00'));
        $month=strtotime(date('Y-m-01 00:00:00'));



//        //今日签约数
//        $data['todayNum']=ChildInfo::find()
//            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
//            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
//            ->andFilterWhere([">",'`doctor_parent`.createtime',$today])
//            ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
//            ->andWhere(['>','child_info.admin',0])
//            ->count();
//        //签约儿童总数
//        $data['todayNumTotal']=ChildInfo::find()
//            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
//            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
//            ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
//            ->andWhere(['>','child_info.admin',0])
//            ->count();
//
//        //管辖儿童数
//        $data['childNum']=ChildInfo::find()
//            ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
//            ->andFilterWhere(['>','child_info.source',0])
//            ->count();
//        //签约率
//        if($data['childNum']) {
//            $data['baifen'] = round(($data['todayNumTotal'] / $data['childNum']) * 100,1);
//        }else{
//            $data['baifen'] = 0;
//        }

        //宣教总次数
        $data['articleNum']=ArticleUser::find()
            ->count();

        $data['articleNumToday']=ArticleUser::find()
            ->andFilterWhere(['>','createtime',$today])
            ->count('distinct childid');
        $data['articleNumMonth']=ArticleUser::find()
            ->andFilterWhere(['>','createtime',$month])
            ->count('distinct childid');

        $data['articleNoMonth']=$data['todayNumTotal']-$data['articleNumMonth'];

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
                ->count();

            $rs['day']=$date;
            $line_data[]=$rs;
        }

        $now=ChildInfo::find()
            ->leftJoin('user','`user`.`id`=`child_info`.`userid`')
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`'=>1])
            ->orderBy('`doctor_parent`.`createtime` desc')->limit(9)->all();

        $user['qrcodeNum']=WeOpenid::find()
            ->andFilterWhere(['>','createtime',$today])
            ->count();
        $user['TqrcodeNum']=WeOpenid::find()->count();

        $user['dpNum']=DoctorParent::find()
            ->andFilterWhere(['>','createtime',$today])
            ->count();
        $user['TdpNum']=DoctorParent::find()->count();

        $user['qrcodeNoneNum']=WeOpenid::find()
            ->andWhere(['=','xopenid',''])
            ->andFilterWhere(['>','createtime',$today])
            ->count();
        $user['TqrcodeNoneNum']=WeOpenid::find()
            ->andWhere(['=','xopenid',''])
            ->count();

        $doctor=UserDoctor::find()->all();

        return $this->render('index',[
            'data'=>$data,
            'line_data'=>$line_data,
            'now'=>$now,
            'doctor'=>$doctor,
            'user'=>$user,

        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
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
