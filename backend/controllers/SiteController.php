<?php

namespace backend\controllers;

use common\models\ArticleUser;
use common\models\DoctorParent;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
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
        $doctorParent=DoctorParent::find()->select('parentid')->andFilterWhere([">",'createtime',$today])->andFilterWhere(['level'=>1])->column();
        if($doctorParent) {
            $data['todayNum'] = \common\models\ChildInfo::find()->andFilterWhere(['in', 'userid', $doctorParent])->count();
        }else{
            $data['todayNum']=0;
        }

        //管理儿童数
        $doctorParentTotal=DoctorParent::find()->select('parentid')->andFilterWhere(['level'=>1])->column();
        if($doctorParentTotal) {
            $data['todayNumTotal'] = \common\models\ChildInfo::find()->andFilterWhere(['in', 'userid', $doctorParentTotal])->count();
        }else{
            $data['todayNumTotal']=0;
        }



        //管辖儿童数
        $child=\common\models\ChildInfo::find()->andFilterWhere(['>','birthday',strtotime('-3 year')]);
        $child->andFilterWhere(['>','admin',0]);

        $data['childNum']=$child->count();
        //签约率
        if($data['childNum']) {
            $data['baifen'] = round(($data['todayNumTotal'] / $data['childNum']) * 100,1);
        }else{
            $data['baifen'] = 0;
        }





        //宣教总次数
        $data['articleNum']=ArticleUser::find()->count();
        //规范化指导次数
        $data['articleNumType']=ArticleUser::find()->innerJoinWith('article')->andFilterWhere(['article.type'=>1])->count();



        //宣教查看次数
        $data['articleLockNum1']=ArticleUser::find()->innerJoinWith('article')->andFilterWhere(['article.type'=>1])->andFilterWhere(['article_user.level'=>2])->count();

        $data['articleLockNum2']=ArticleUser::find()->innerJoinWith('article')->andFilterWhere(['article.type'=>0])->andFilterWhere(['article_user.level'=>2])->count();

        //var_dump($parents);exit;


        //宣教覆盖儿童数
        $parents=ArticleUser::find()->select('touserid')->groupBy('touserid')->column();
        if($parents) {
            $article_child = \common\models\ChildInfo::find()->andFilterWhere(['in', 'userid', $parents]);
            $data['articleChildNum'] = $article_child->andFilterWhere(['in', 'userid', $parents])->count();

        }else{
            $data['articleChildNum'] = 0;
        }


        return $this->render('index',[
            'data'=>$data,
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
