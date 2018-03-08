<?php
/**
 * 上传图片
 */
namespace app\components\upload;

use yii\base\Model;

class UploadFile extends Model
{
    private static function obtain()
    {
        $accessKey = "SYS000000000010086";
        $secretKey = "1111111111111111111111111111111111111111";
        $project = 'media.xywy.com';
        return $o = \XywyStorageService::getInstance($project, $accessKey, $secretKey);
    }
    public static function upload($file)
    {
        $o = self::obtain();
        $o->setCURLOPTs(array(CURLOPT_VERBOSE => 1));
        $time = date('Ymd', time());
        $file_new_name = substr(md5(time() . '@$%&'), 15, -2);
        $host = \Yii::$app->request->hostInfo;
        if ($host != 'http://admin.media.xywy.com'){
            $path = 'test';
        } else {
            $path = 'article';
        }
        $url = $path . "/" . $time . "/" . $file_new_name;
        $o->uploadFile($url, $file, $result);
        $o->getFileUrl($url, $result);
        $result = str_replace('?', '', $result);
        return $result;
    }

    public function delete($url)
    {
        $result = "";
        $this->obtain()->deleteFile($url, $result);
        return $result;
    }


}