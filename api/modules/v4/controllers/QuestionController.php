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
use yii\data\Pagination;
use yii\web\UploadedFile;

class QuestionController extends Controller
{
    public function actionPut()
    {
        $post = \Yii::$app->request->post();
        $content = $post['content'];
        $questionid = Question::Create($this->userid,  $content);

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
    public function actionList(){
        $question=Question::find()->where(['level'=>1]);
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

        return ['question'=>$rs];
    }
}