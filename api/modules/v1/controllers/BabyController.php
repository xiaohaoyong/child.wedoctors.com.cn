<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/30
 * Time: 上午11:51
 */

namespace api\modules\v1\controllers;


use common\models\BabyToolLike;
use yii\data\Pagination;

class BabyController extends \api\controllers\BabyController
{
    public function actionCollectionList()
    {
        $query=BabyToolLike::find()->andFilterWhere(['userid'=>$this->userid])->andFilterWhere(['type'=>3]);
        $totle=$query->count();
        $pages = new Pagination(['totalCount' =>$totle, 'pageSize' => 10]);
        $datas = $query->offset($pages->offset)->limit($pages->limit)->all();
        foreach ($datas as $k=>$v){
            $row=$v->toArray();
            $row['createtime']=date('Y-m-d',$row['createtime']);
            $row['num']=BabyToolLike::find()->andFilterWhere(['type'=>3,'bid'=>$v->bid])->count();
            $row['info']=$v->tag;
            $list[]=$row;
        }

        return ['list'=>$list,'totle'=>ceil($totle/10)];
    }
}