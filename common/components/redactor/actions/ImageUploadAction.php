<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\components\redactor\actions;

use OSS\Core\OssException;
use OSS\OssClient;
use Yii;
use yii\redactor\models\ImageUploadModel;
use yii\web\UploadedFile;

/**
 * @author Nghia Nguyen <yiidevelop@hotmail.com>
 * @since 2.0
 */
class ImageUploadAction extends \yii\base\Action
{
    function run()
    {
        $imagesFile =UploadedFile::getInstancesByName('file');
        $image=$imagesFile[0];
        if (isset($_FILES)) {
            $time=time();
            $filen=substr(md5($time.$_FILES['tmp_name']),4,14).".".$image->extension;
            $images='http://static.i.wedoctors.com.cn/redactor/'.$filen;

            try{
                $ossClient = new OssClient('LTAIteFpOZnX3aoE', 'lYWI5AzSjQiZWBhC2d7Ttt06bnoDFF', 'oss-cn-qingdao.aliyuncs.com');
                $ossClient->uploadFile('childimage', 'redactor/'.$filen, $image->tempName);

                return [
                    'filelink'=>$images,
                    'filename'=>$filen
                ];


            } catch(OssException $e) {
                print_r($e->getMessage());exit;
            }
        }
    }
    /**
     * 根据二进制判断文件格式
     * @param $content 二进制流
     */
    public function filetype2($content)
    {
        $bin = substr($content,0,2);
        $strInfo = @unpack("C2chars", $bin);
        $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
        $fileType = '';
        switch ($typeCode)
        {
            case 7790:
                $fileType = 'exe';
                break;
            case 7784:
                $fileType = 'midi';
                break;
            case 8297:
                $fileType = 'rar';
                break;
            case 255216:
                $fileType = 'jpg';
                break;
            case 7173:
                $fileType = 'gif';
                break;
            case 6677:
                $fileType = 'bmp';
                break;
            case 13780:
                $fileType = 'png';
                break;
            default:
                echo 'unknown';
        }
        return $fileType;
    }

}
