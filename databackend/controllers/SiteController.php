<?php
namespace databackend\controllers;

use common\models\ArticleUser;
use common\models\ChildInfo;
use common\models\UserDoctor;
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
                        'actions' => ['logout','index','login', 'error', 'captcha'],
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

        $hospitalids=ArrayHelper::getColumn($this->doctor,'hospitalid');
        $doctorids=ArrayHelper::getColumn($this->doctor,'userid');

        //今日签约数
        $today=strtotime(date('Y-m-d 00:00:00'));
        $month=strtotime(date('Y-m-01 00:00:00'));



        //今日签约数
        $data['todayNum']=ChildInfo::find()
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
            ->andFilterWhere(['in','`doctor_parent`.`doctorid`' ,$doctorids])
            ->andFilterWhere([">",'`doctor_parent`.createtime',$today])
            ->count();

        //签约儿童总数
        $data['todayNumTotal']=ChildInfo::find()
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
            ->andFilterWhere(['in','`doctor_parent`.`doctorid`' ,$doctorids])
            ->andFilterWhere(['in','child_info.doctorid',$hospitalids])

            ->count();


        //管辖儿童数
        $data['childNum']=ChildInfo::find()
            ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
            ->andFilterWhere(['in','child_info.source',$hospitalids])
            ->count();

        //签约率
        if($data['childNum']) {
            $data['baifen'] = round(($data['todayNumTotal'] / $data['childNum']) * 100,1);
        }else{
            $data['baifen'] = 0;
        }

        //宣教总次数
        $data['articleNum']=ArticleUser::find()
            ->andFilterWhere(['in','article_user.userid' ,$doctorids])
            ->count();

        $data['articleNumToday']=ArticleUser::find()
            ->andFilterWhere(['>','createtime',$today])
            ->andFilterWhere(['in','article_user.userid' ,$doctorids])
            ->count('distinct childid');
        $data['articleNumMonth']=ArticleUser::find()
            ->andFilterWhere(['>','createtime',$month])
            ->andFilterWhere(['in','article_user.userid' ,$doctorids])
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
                ->andFilterWhere(['in','`doctor_parent`.`doctorid`' ,$doctorids])
                ->count();

            $rs['day']=$date;
            $line_data[]=$rs;
        }

        $now=ChildInfo::find()
            ->leftJoin('user','`user`.`id`=`child_info`.`userid`')
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`'=>1])
            ->andFilterWhere(['in','`doctor_parent`.`doctorid`' ,$doctorids])
            ->orderBy('`doctor_parent`.`createtime` desc')->limit(9)->all();


        $doctor=UserDoctor::find()->andFilterWhere(['county'=>\Yii::$app->user->identity->county])->andFilterWhere(['>','userid',37])->all();

        return $this->render('index',[
            'data'=>$data,
            'line_data'=>$line_data,
            'now'=>$now,
            'doctor'=>$doctor

        ]);
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
