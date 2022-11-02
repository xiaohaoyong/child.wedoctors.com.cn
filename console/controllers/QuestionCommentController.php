<?php
/**
 * 1、被回复后24个小时内无新提问，结束会话，推送小程序消息评价邀请，
 * 点击小程序进入会话进行评价，评价完成，吐司提示并关闭页面。

 */

namespace console\controllers;

use common\models\Question;
use common\models\QuestionReply;
use yii\base\Controller;

class QuestionCommentController extends Controller
{
    public function actionComment()
    {
        echo 'ok';
        //已回复 后的24小时没有在回复，结束会话
        $query = Question::find()->where(['state'=>1]);
        $total = $query->count();
        $size = 100; //一个excel5000条数据
        $page = ceil($total/$size);
        for($i=0;$i<$page;$i++) {
            $list = $query->orderBy('id desc')->offset($i * $size)->limit($size)->asArray()->all();
            if ($list) {

                foreach ($list as $k => $val) {

                        $questionReply = QuestionReply::find()->where(['qid'=>$val['id']])->orderBy('id desc')->one();
                        var_dump($questionReply);die;
                }
            }
        }

    }

}