<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/20
 * Time: 下午2:46
 */

namespace api\modules\v2\controllers;

use api\controllers\Controller;
use common\components\Code;
use common\models\ClockIn;
use common\models\Points;
use yii\data\Pagination;

class PointController extends Controller
{
    public function actionList(){
        $points=Points::find();


        $pages = new Pagination(['totalCount' => $points->count(), 'pageSize' => 10]);
        $list = $points->where(['userid'=>$this->userid])->orderBy('id desc')->offset($pages->offset)->limit($pages->limit)->all();

        foreach($list as $k=>$v)
        {
            $row=$v->toArray();
            $row['sourceText']=Points::$sourceText[$v->source];
            $row['timeText']=date('Y.m.d',$v->createtime);

            $data['list'][]=$row;
        }
        $data['pageTotal']=ceil($points->count()/10);

        return $data;
    }
}