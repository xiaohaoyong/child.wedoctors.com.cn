<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2016/9/22
 * Time: 15:59
 */

namespace app\components\helper;


use yii\base\Object;
use yii\web\UploadedFile;

class UploadHelper extends Object
{
    public static $fileName;
    /**
     * 获取上传文件的二进制数据并执行上传
     * @return mixed
     */
    public static function updateImage(UploadedFile $imageFile)
    {
        self::$fileName=$imageFile->tempName;
        //$fileNamet=$img["name"];
        //$handle=fopen($fileName,"r");//使用打开模式为r

        return self::Uimpage();
    }

    public static function Uimpage()
    {
        //$img_array = get_headers($fileName,true);
        $content=file_get_contents(self::$fileName);//读为二进制
        $filetype=self::filetype2($content);
        $time=time();
        $filen=substr(md5($time.self::$fileName),4,14);
        return self::upload($filen.'.'.$filetype,base64_encode($content));
    }

    public static function uploadDir()
    {
        return __ROOT__."/../".\Yii::$app->params['imageDir']."/upload/";
    }

    /**
     * 上传文件操作
     * @param $filename
     * @param $content
     * @param string $dir
     * @param bool $thumb
     * @param int $thumb_width
     * @param int $thumb_height
     * @return bool|mixed
     */
    public static function upload($filename,$content,$dir='doc',$thumb = true,$thumb_width=150,$thumb_height=150)
    {
        file_put_contents(self::uploadDir() .$filename,$content);

        return \Yii::$app->params['imageUrl'].$filename;
    }
    public static function  filetype2($content)
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