<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/6/19
 * Time: 下午3:56
 */

namespace frontend\controllers;


use common\models\QuestionNaire;
use common\models\QuestionNaireAnswer;
use common\models\QuestionNaireAsk;

class QuestionNaireController extends QnController
{
    public function actionForm($id,$doctorid=0)
    {
        $qnaa=QuestionNaireAnswer::findOne(['qnid'=>$id,'userid'=>$this->login->userid]);

        if($qnaa){
            return $this->redirect(['question-naire/healthy','id'=>$id]);

        }
        $qnaa = new QuestionNaireAnswer();
        $qn = QuestionNaire::findOne($id);
        $qna = QuestionNaireAsk::findAll(['qnid' => $id]);
        $post=\Yii::$app->request->post()['QuestionNaireAnswer'];
        if ($post) {
            foreach($post as $k=>$v){
                foreach($post[$k] as $pk=>$pv) {
                    $qnaa = new QuestionNaireAnswer();
                    $qnaa->phone=15811078604;
                    $qnaa->value='';
                    $qnaa->idcode='230107198908232610';
                    $qnaa->answer=$pv;
                    $qnaa->doctorid=$doctorid;
                    $qnaa->qnaid=$pk;
                    $qnaa->qnid=$id;
                    $qnaa->userid=$this->login->userid;
                    $qnaa->save();
                }
            }
            return $this->redirect(['question-naire/healthy','id'=>$id]);

        }
        return $this->render('form', [
            'qn' => $qn,
            'qna' => $qna,
            'qnaa' => $qnaa
        ]);
    }

    public function actionHealthy($id){
        $qnaa=QuestionNaireAnswer::findOne(['qnid'=>$id,'userid'=>$this->login->userid,'answer'=>1]);
        if($qnaa){
            $is_healthy=false;
        }else{
            $is_healthy=true;
        }
        return $this->render('healthy',[
            'is_healthy'=>$is_healthy,
            'id'=>$id
        ]);
    }
    public function actionView($id){
        $qnaa=QuestionNaireAnswer::find()->where(['qnid'=>$id,'userid'=>$this->login->userid])->indexBy('qnaid')->all();
        $qn = QuestionNaire::findOne($id);
        $qna = QuestionNaireAsk::findAll(['qnid' => $id]);
        return $this->render('view', [
            'qn' => $qn,
            'qna' => $qna,
            'qnaa' => $qnaa
        ]);
    }

}