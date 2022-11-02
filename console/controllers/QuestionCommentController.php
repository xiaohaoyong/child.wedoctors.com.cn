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
        echo 'start';
        //已回复 后的24小时没有在回复，结束会话
        $query = Question::find()->where(['state'=>1]);
        $total = $query->count();
        $size = 100; //一个excel5000条数据
        $page = ceil($total/$size);
        for($i=0;$i<$page;$i++) {
            $list = $query->orderBy('id desc')->offset($i * $size)->limit($size)->asArray()->all();
            if ($list) {

                foreach ($list as $k => $val) {
    //获取最后一条回复时间
                        $questionReply = QuestionReply::find()->where(['qid'=>$val['id']])->orderBy('id desc')->one();
                        if($questionReply){
                            $current_time = time();
                            $last_time = strtotime("+1 day",$questionReply->createtime); // 2022年11月1日
                            if($current_time >$last_time){
                                echo $val['id'].'-'.date("Y-m-d H:i:s",$last_time)."\n";
                                //超过24小时没有回复，问题自动结束
                                Question::updateAll(['state'=>3],['id'=>$val['id']]);
                            }

                        }


                }
            }
        }
        echo 'end';

    }

}