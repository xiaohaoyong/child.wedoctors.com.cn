<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/10/20
 * Time: 下午3:48
 */

namespace frontend\controllers;


use common\models\BabyGuide;
use common\models\BabyTool;
use common\models\BabyToolLike;
use common\models\BabyToolTag;

class ApiBabytoolController extends ApiController
{
    public function actionAge(){
        $tags=BabyToolTag::find()->select('id,tag,name')->orderBy('week asc,id asc')->all();
        return $tags;
    }
    public function actionList($period){
        $list=BabyTool::findAll(['period'=>$period]);
        $nlist=BabyGuide::findAll(['period'=>$period]);
        $likeCount=BabyToolLike::find()->where(['bid'=>$period,'type'=>1])->count();

        return ['list'=>$list,'nlist'=>$nlist,'likeCount'=>$likeCount];
    }
    public function actionView($id){

    }

}