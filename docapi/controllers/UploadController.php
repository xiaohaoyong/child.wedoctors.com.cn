<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/5/14
 * Time: 上午9:58
 */

namespace docapi\controllers;


use common\components\Code;
use yii\web\UploadedFile;

class UploadController extends Controller
{
    public function actionImg(){
        $imagesFile = UploadedFile::getInstancesByName('file');



        if($imagesFile) {
            $upload= new \common\components\UploadForm();
            $upload->imageFiles = $imagesFile;
            $image = $upload->upload();
            if($image[0]){
                return $image[0];
            }
        }
        return new Code(20010,'上传失败');
    }

}