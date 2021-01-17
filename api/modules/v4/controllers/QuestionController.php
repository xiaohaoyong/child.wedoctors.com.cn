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
}