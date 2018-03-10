<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/25 0025
 * Time: 0:28
 */
namespace api\models;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $baseName=md5($this->imageFile->baseName) . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs( __DIR__ . '/../../uploads/certificate/' . $baseName);
            return $baseName;
        } else {
            return false;
        }
    }
}