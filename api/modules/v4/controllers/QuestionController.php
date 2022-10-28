<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2021/1/15
 * Time: 上午11:30
 */

namespace api\modules\v4\controllers;


use api\controllers\Controller;
use common\components\Code;
use common\models\DoctorParent;
use common\models\Merge;
use common\models\Question;
use common\models\QuestionImg;
use common\models\QuestionInfo;
use common\models\QuestionReply;
use common\models\UserDoctor;
use yii\data\Pagination;
use yii\web\UploadedFile;
use common\models\QuestionComment;

class QuestionController extends Controller
{
    public function actionPut($id)
    {
       // $post = \Yii::$app->request->post();
        $post = \Yii::$app->request->get();
        $question = new Question();
        $question->userid = $this->userid;
        $question->doctorid=5;
        $question->loginid=0;

        if ($question->save()) {
            $quesInfo = new QuestionInfo();
            $post['birthday']=strtotime($post['birthday']);
            $quesInfo->load(['QuestionInfo'=>$post]);
            $quesInfo->qid = $question->id;
            $quesInfo->save();
            if($quesInfo){
                if(strpos($post['content'],'新冠') !== false) {
                    $merge = new Merge();
                    $query_f = [
                        "match" => [
                            "title" => [
                                "query" => $post['content'],
                            ]
                        ]
                    ];
                    $query = $merge::find()->query($query_f)->all();
                    if ($query[0] && $query[0]->score > 2) {
                        $reply = Merge::$question[$query[0]->content];
                        if ($reply) {
                            $questionReply = new QuestionReply();
                            $questionReply->content = $reply;
                            $questionReply->is_doctor = 1;
                            $questionReply->userid = $id;
                            $questionReply->qid = $question->id;
                            if($questionReply->save()){
                                $question->state=1;
                                $question->save();
                            }

                        }
                    }
                }
            }
//            $quesTag = new QuestionTag();
//            foreach ($tag as $k => $v) {
//                $quesTag->qid = $question->id;
//                $quesTag->tagid = $v;
//                $quesTag->save();
//            }
//
//            $order = Order::findOne(['orderid' => $orderid]);
//            if ($order) {
//                $order->status = 2;
//                $order->save();
//            }
//            AskChatRoom::createRoom($order->id,$order->userid);

            return $question->id;
        }


        return new Code(20000,'失败');
    }

    public function actionImg()
    {
        $qid=\Yii::$app->request->post('qid');
        $imagesFile = UploadedFile::getInstancesByName('file');
        if($imagesFile) {
            $upload= new \common\components\UploadForm();
            $upload->imageFiles = $imagesFile;
            $image = $upload->upload();
            $quesImg=new QuestionImg();
            $quesImg->image=$image[0];
            $quesImg->qid=$qid;
            $quesImg->save();


            if($quesImg->firstErrors){
                return new Code(20010,$quesImg->firstErrors);
            }
        }

    }
    public function actionList($type,$doctorid=0){

        $question=Question::find()->where(['level'=>1]);
        if($doctorid) {
            $question->andWhere(['doctorid'=>$doctorid]);
        }
        if($type){
            $question->andWhere(['userid'=>$this->userid]);
        }
        $pages = new Pagination(['totalCount' => $question->count(), 'pageSize' => 10]);
        $list = $question->orderBy('id desc')->offset($pages->offset)->limit($pages->limit)->all();
        foreach($list as $k=>$v){
            $rs=$v->toArray();
            $rs['state']=Question::$stateText[$v->state];
            $rs['createtime']=date('m-d',$v->createtime);
            $rs['imgs']=QuestionImg::findAll(['qid'=>$v->id]);
            $rs['info']=QuestionInfo::findOne(['qid'=>$v->id]);
            $data['list'][]=$rs;
        }
        $data['pageTotal']=ceil($question->count()/10);

        return $data;
    }

    public function actionView($id){
        $question=Question::findOne($id);
        $rs=$question->toArray();
        $rs['state']=Question::$stateText[$question->state];
        $rs['createtime']=date('m-d',$question->createtime);
        $rs['imgs']=QuestionImg::find()->where(['qid'=>$question->id])->select('image')->column();
        $rs['info']=QuestionInfo::findOne(['qid'=>$question->id]);

        $reply=QuestionReply::find()->where(['qid'=>$id])->limit(10)->all();
        foreach($reply as $k=>$v){
            $row=$v->toArray();
            if($v->is_doctor){
                $doctor=UserDoctor::findOne(['userid'=>$v->userid]);
                $row['user']=$doctor->name;
            }else{
                $row['user']="用户".$v->userid;
            }
            $row['createtime']=date('m-d',$v->createtime);
            $replys[]=$row;
        }
        $query=QuestionReply::find()->where(['level'=>1,'qid'=>$id]);
        $data['pageTotal']=ceil($query->count()/10);

        if($question->userid==$this->userid){
            $is_my=1;
        }else{
            $is_my=0;
        }

        return ['question'=>$rs,'replys'=>$replys,'is_my'=>$is_my];
    }

    public function actionReplys($id){
        $replys=QuestionReply::find()->where(['level'=>1,'qid'=>$id]);
        $pages = new Pagination(['totalCount' => $replys->count(), 'pageSize' => 10]);
        $list = $replys->offset($pages->offset)->limit($pages->limit)->all();
        foreach($list as $k=>$v){
            $row=$v->toArray();
            if($v->is_doctor){
                $doctor=UserDoctor::findOne(['userid'=>$v->userid]);
                $row['user']=$doctor->name;
            }else{
                $row['user']="用户".$v->userid;
            }
            $row['createtime']=date('m-d',$v->createtime);
            $replys[]=$row;
        }

        return $replys;
    }

    public function actionReply($id){

        $question=Question::findOne($id);
        if($question->userid==$this->userid) {
            $content = \Yii::$app->request->post('content');
            $question_reply = new QuestionReply();
            $question_reply->content = $content;
            $question_reply->userid = $this->userid;
            $question_reply->qid = $id;
            $question_reply->save();
        }
        return ;
    }
    /**
     * 推送消息发送评价页面
     * 问题已结束才能评价，一个问题只能评价一次
     * 问题评论
     */
    public function actionComment(){
        $qid = \Yii::$app->request->get('id'); //问题ID
        $satisfied = \Yii::$app->request->get('satisfied'); //满意 1不满意 2满意
        $solve = \Yii::$app->request->get('solve');//1未解决2已解决
        $question = Question::findOne(intval($qid));
        $msg = '问题不存在或还未结束';
        if(empty($satisfied) || empty($solve)){
            $msg = '参数有误!';
            return new Code(20000,$msg);
        }
        //已结束
        if($question && $question->state == 2){
            //一个问题只能评论一次
            $questionComment = QuestionComment::find()->where(['qid'=>$question->id])->one();
            if($questionComment){
                $msg = '问题已提交过评价，不可重复评价';
                return new Code(20000,$msg);
            }else{
                $comment = new QuestionComment();
                $comment->qid = $question->id;
                $comment->userid = $this->userid;
                $comment->is_satisfied = intval($satisfied);
                $comment->is_solve = intval($solve);
                $comment->createtime = time();
                $comment->save(false);
                //修改问题为已评价
                Question::updateAll(['is_comment'=>1],['id'=>$question->id]);

            }
        }else{
            return new Code(20000,$msg);
        }



        // var_dump($qid);die;
    }
}