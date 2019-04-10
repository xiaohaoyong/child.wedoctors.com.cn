<?php
namespace api\components\base;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/7/24
 * Time: 下午4:45
 */

class Application extends \yii\web\Application
{
    private $version_number=[
        0=>'v1',
        1=>'v2',
        2=>'v3',
    ];
    public function createController($route)
    {
        $result = parent::createController($route);
        list ($id, $route) = explode('/', $route, 2);
        $v=array_search($id,$this->version_number);
        defined('YIMAI_VERSION') or define('YIMAI_VERSION',str_replace('t','.', $this->version_number[$v]));
        if(!$result){
            if (strpos($route, '/') !== false) {
                if($this->version_number[$v]!==false) {
                    $v = $v ? $v - 1 : 0;
                    $module = $this->getModule($this->version_number[$v]);
                    if ($module !== null) {
                        $result = $module->createController($route);
                    }
                }else{
                    throw new NotFoundHttpException('version is not!',404);
                }
            }
        }
        return $result;
    }
}