<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/8/9
 * Time: 下午4:24
 */

namespace docapi\modules\v1\controllers;


use common\models\Article;
use common\models\ArticleSend;
use common\models\ArticleUser;
use common\models\Doctors;
use yii\web\Controller;

class ArticleSendController extends \docapi\controllers\Controller
{
    public function actionIndex(){
        $doctor = Doctors::findOne(['userid' => $this->userid]);

        $doctorid = \common\models\UserDoctor::findOne(['hospitalid' => $doctor->hospitalid])->userid;
        foreach (\common\models\Article::$childText as $k => $v) {
            if($k>0) {
                $row['yue'] = $v;
                $row['none'] = \common\models\ArticleUser::noSendChildNum($k, $doctorid);
                $row['done'] = \common\models\ArticleUser::find()->where(['userid' => $doctorid])->andWhere(['child_type' => $k])->count();
                $row['id'] = $k;
                $list[] = $row;
            }
        }
        return $list;
    }

    public function actionView($type){
        $results['list']=[];
        $results['list1']=[];
        $list=Article::find()->where(['child_type'=>$type,'type'=>1])->all();
        foreach($list as $k=>$v)
        {
            $results['list'][] = \weixin\models\Article::findById($v->id);
        }
        $list=Article::find()->where(['child_type'=>$type,'type'=>0])->orderBy('id desc')->limit(10)->all();
        foreach($list as $k=>$v)
        {
            $results['list1'][] = \weixin\models\Article::findById($v->id);
        }
        return $results;
    }

    public function actionSendView($type){
        $params=\Yii::$app->request->post();
        $doctor = Doctors::findOne(['userid' => $this->userid]);
        $params['artid']=explode(',',$params['artid']);
        if($params){
            $doctorid=\common\models\UserDoctor::findOne(['hospitalid'=>$doctor->hospitalid])->userid;
            $articleSend=new ArticleSend();
            $articleSend->load(['ArticleSend'=>$params]);
            $articleSend->type=$type;
            $articleSend->doctorid=$doctorid;
            $articleSend->send();
        }
    }

}