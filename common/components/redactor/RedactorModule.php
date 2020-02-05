<?php
namespace common\components\redactor;
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/2/5
 * Time: 上午10:02
 */

class RedactorModule extends \yii\redactor\RedactorModule
{
    public $controllerNamespace = 'common\components\redactor\controllers';
    public $imageUploadRoute = ['/redactor_reset/upload/image'];
    public $imageManagerJsonRoute = ['/redactor/upload/image-json'];

}