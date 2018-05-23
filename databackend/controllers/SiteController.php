<?php
namespace databackend\controllers;

use databackend\models\article\ArticleUser;
use databackend\models\user\ChildInfo;
use databackend\models\user\DoctorParent;
use databackend\models\user\UserDoctor;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

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
        $doctorParent=DoctorParent::find()->select('parentid')->andFilterWhere([">",'createtime',$today])->andFilterWhere(['level'=>1])->column();
        if($doctorParent) {
            $data['todayNum'] = \common\models\ChildInfo::find()->andFilterWhere(['in', 'userid', $doctorParent])->count();
        }else{
            $data['todayNum']=0;
        }

        //管理儿童数
        $doctorParentTotal=DoctorParent::find()->select('parentid')->andFilterWhere(['level'=>1])->column();

        //管理儿童数
        $childQuery=\common\models\ChildInfo::find();
        $childQuery->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
        $childQuery->andFilterWhere(['`doctor_parent`.`level`' => 1]);
        if(\Yii::$app->user->identity->hospital) {
            $doctorid = UserDoctor::findOne(['hospitalid' => \Yii::$app->user->identity->hospital])->userid;
            $childQuery->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid]);
        }else{
            $childQuery->andFilterWhere(['not in','`doctor_parent`.`doctorid`',[47118,39889,47156]]);
        }
        $doctorParentTotal = $childQuery->count();

        if($doctorParentTotal) {
            $data['todayNumTotal'] =$doctorParentTotal;
        }else{
            $data['todayNumTotal']=0;
        }



        //管辖儿童数
        $child=\common\models\ChildInfo::find()->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')]);
        if(\Yii::$app->user->identity->type != 1)
        {
            $child->andFilterWhere(['child_info.source'=>\Yii::$app->user->identity->hospital]);
        }else{
            $child->andFilterWhere(['>','child_info.source',38]);
            $child->andFilterWhere(['not in','child_info.source',[110564,110559]]);

        }
        $data['childNum']=$child->count();
        //签约率
        if($data['childNum']) {
            $data['baifen'] = round(($data['todayNumTotal'] / $data['childNum']) * 100,1);
        }else{
            $data['baifen'] = 0;
        }

        //宣教总次数
        $data['articleNum']=ArticleUser::find()->andFilterWhere(['not in','`userid`',[47118,39889,47156]])->count();

        $data['articleNumToday']=ArticleUser::find()->andFilterWhere(['>','createtime',$today])->andFilterWhere(['not in','`userid`',[47118,39889,47156]])->count('distinct childid');
        $data['articleNumMonth']=ArticleUser::find()->andFilterWhere(['>','createtime',$month])->andFilterWhere(['not in','`userid`',[47118,39889,47156]])->count('distinct childid');

        $data['articleNoMonth']=$doctorParentTotal-$data['articleNumMonth'];

        for($i=9;$i>-1;$i--)
        {
            $time=strtotime("-$i day");
            $date=date('Y-m-d',$time);
            $st=strtotime($date."00:00:00");
            $end=$st+3600*24;
            $childQuery=\common\models\ChildInfo::find()
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                ->andFilterWhere(['>','`doctor_parent`.createtime',$st])->andFilterWhere(['<','`doctor_parent`.createtime',$end])
                ->andFilterWhere(['not in','`doctor_parent`.`doctorid`',[47118,39889,47156]])
                ->count();

            //$rs['item1']=ArticleUser::find()->andFilterWhere(['>','createtime',$st])->andFilterWhere(['<','createtime',$end])->count();
            $rs['item1']=$childQuery;

            $rs['day']=$date;
            $line_data[]=$rs;
        }

        $now=ChildInfo::find()
            ->leftJoin('user','`user`.`id`=`child_info`.`userid`')
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`user`.`source`'=>2])
            ->andFilterWhere(['`doctor_parent`.`level`'=>1])
            ->andFilterWhere(['not in','`doctor_parent`.`doctorid`',[47118,39889,47156]])
            ->orderBy('`doctor_parent`.`createtime` desc')->limit(9)->all();


        $doctor=UserDoctor::find()->andFilterWhere(['county'=>1102])->andFilterWhere(['>','userid',37])->all();





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

        $model = new LoginForm();             //②
        if ($model->load(Yii::$app->request->post()) && $model->login()) {      //③
            return $this->goBack();          //④
        }
        return $this->renderPartial('login', [      //⑤
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
