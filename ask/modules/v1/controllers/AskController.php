<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/4/23
 * Time: 下午12:56
 */

namespace ask\modules\v1\controllers;


use ask\controllers\Controller;
use common\components\Code;
use common\models\ChildInfo;
use common\models\Question;
use common\models\QuestionImg;
use common\models\Tag;
use yii\web\UploadedFile;

class AskController extends Controller
{
    public function actionView()
    {
        $tag = Tag::find()->select('id,name')->all();
        $children = ChildInfo::find()->select('id,name')->where(['userid' => $this->userid])->orderBy('id desc')->all();


        return ['tag' => $tag, 'child' => $children];
    }

    public function actionPut()
    {
        $post = \Yii::$app->request->post();
        $content = $post['content'];
        $tag = explode(',', $post['tag']);
        $childid = $post['childid'];
        $orderid = $post['orderid'];
        $questionid = Question::Create($orderid, $this->userid, $childid, $content, $tag);

        return $questionid;
    }

    public function actionImg()
    {
        $qid=\Yii::$app->request->post('qid');
        $imagesFile = UploadedFile::getInstancesByName('file');
        $img=$imagesFile[0]->tempName;

        $rotang = 90; // Rotation angle
        $source = imagecreatefrompng($img) or die('Error opening file '.$img);
        imagealphablending($source, false);
        imagesavealpha($source, true);

        $rotation = imagerotate($source, $rotang, imageColorAllocateAlpha($source, 0, 0, 0, 127));
        imagealphablending($rotation, false);
        imagesavealpha($rotation, true);
        imagepng($rotation,$img);

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