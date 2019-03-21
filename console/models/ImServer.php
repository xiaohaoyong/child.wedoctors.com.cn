<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/7/27
 * Time: 下午3:03
 */

namespace console\models;


use common\models\DataUser;
use common\models\DataUserTask;
use console\controllers\ChildExcController;
use http\Url;
use jianyan\websocket\server\WebSocketServer;
use yii\helpers\Html;

class ImServer extends WebSocketServer
{
}