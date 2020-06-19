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
    public function actionForm($id){
        $qn=QuestionNaire::findOne($id);
        $qna=QuestionNaireAsk::findAll(['qnid'=>$id]);
        $qnaa=new QuestionNaireAnswer();
        return $this->render('form',[
            'qn'=>$qn,
            'qna'=>$qna,
            'qnaa'=>$qnaa
        ]);
    }

}