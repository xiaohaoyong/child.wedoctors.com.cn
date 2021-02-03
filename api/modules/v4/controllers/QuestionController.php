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
use common\models\Question;
use common\models\QuestionImg;
use common\models\QuestionInfo;
use common\models\QuestionReply;
use common\models\UserDoctor;
use yii\data\Pagination;
use yii\web\UploadedFile;

class QuestionController extends Controller
{
    public function actionPut($id)
    {
        $post = \Yii::$app->request->post();
        $content = $post['content'];
        $questionid = Question::Create($this->userid,  $content,$id);

        return $questionid;
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
    public function actionList($type){
        $question=Question::find()->where(['level'=>1]);
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
}