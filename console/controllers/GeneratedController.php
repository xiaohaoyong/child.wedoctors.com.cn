<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/7/10
 * Time: 上午10:22
 */

namespace console\controllers;
use common\models\QuestionNaireAnswer;
use yii\base\Controller;


class GeneratedController extends Controller
{
    public function actionQuestion1(){
        $start=strtotime(date('Ymd'));
        $end=strtotime(date('Ymd235959'));
        $quaa=QuestionNaireAnswer::find()->select('userid,createtime,doctorid')->groupBy('userid,createtime')->orderBy('createtime desc')->count();
        var_dump($quaa);exit;
        foreach($quaa as $k=>$v){
            system("wkhtmltopdf 'http://web.child.wedoctors.com.cn/question-naire/new-view?id=1&time=".$v->createtime."&userid=".$v->userid."' /tmp/Question1.pdf\n");
            system("ossutil64 cp /tmp/Question1.pdf oss://webdoctorsquestion/".$v->doctorid."/".date('Ymd',$v->createtime)."/".$v->userid.".pdf -f");
        }








    }
}