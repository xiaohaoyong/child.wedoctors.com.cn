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
        $qnaa=QuestionNaireAnswer::find()->where(['qnid'=>$id,'userid'=>$this->login->userid])->orderBy('id desc')->one();
        if($qnaa && strtotime('+1 day',$qnaa->createtime) >time()){
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
                    $qnaa->createtime=time();
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

        $qnaa=QuestionNaireAnswer::find()->where(['qnid'=>$id,'userid'=>$this->login->userid])->orderBy('id desc')->one();
        if(time()>strtotime('+1 day',$qnaa->createtime))
        {
            return $this->redirect(['question-naire/form','id'=>$id,'doctorid'=>$qnaa->doctorid]);
        }
        $qnaa1=QuestionNaireAnswer::findOne(['qnid'=>$id,'userid'=>$this->login->userid,'answer'=>1,'createtime'=>$qnaa->createtime]);

        if($qnaa1){
            $is_healthy=false;
        }else{
            $qnaa2=QuestionNaireAnswer::findOne(['qnid'=>$id,'userid'=>$this->login->userid,'createtime'=>$qnaa->createtime]);
            $is_healthy=true;
        }
        return $this->render('healthy',[
            'is_healthy'=>$is_healthy,
            'qnaa'=>$qnaa2,
            'id'=>$id
        ]);
    }
    public function actionView($id,$time){
        $qnaa=QuestionNaireAnswer::find()->where(['qnid'=>$id,'userid'=>$this->login->userid,'createtime'=>$time])->indexBy('qnaid')->all();
        $qn = QuestionNaire::findOne($id);
        $qna = QuestionNaireAsk::findAll(['qnid' => $id]);
        return $this->render('view', [
            'qn' => $qn,
            'qna' => $qna,
            'qnaa' => $qnaa
        ]);
    }

}