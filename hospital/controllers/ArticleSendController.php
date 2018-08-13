<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/8/9
 * Time: 下午4:24
 */

namespace hospital\controllers;


use common\models\Article;
use common\models\ArticleSend;
use common\models\ArticleUser;
use yii\web\Controller;

class ArticleSendController extends BaseController
{
    public function actionIndex(){
        return $this->render('index');
    }

    public function actionSendView($type){
        $params=\Yii::$app->request->post();
        if($params){
            $doctorid=\common\models\UserDoctor::findOne(['hospitalid'=>\Yii::$app->user->identity->hospital])->userid;
            $articleSend=new ArticleSend();
            $articleSend->load(['ArticleSend'=>$params]);
            $articleSend->type=$type;
            $articleSend->doctorid=$doctorid;
            $articleSend->send();
            \Yii::$app->getSession()->setFlash('success','成功');
            return $this->redirect(['index']);

        }

        return $this->render('send-view',['type'=>$type]);
    }
}