<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/10/20
 * Time: 下午3:48
 */

namespace frontend\controllers;


use common\models\Article;
use yii\data\Pagination;

class ApiArticleController extends ApiController
{
    public function actionCats(){

    }
    public function actionList($catid=0){
        $articles=Article::find();
        if(intval($catid))
        {
            $articles->andFilterWhere(['catid'=>$catid]);
        }else{
            $articles->andFilterWhere(['!=','catid',6]);
            $articles->andFilterWhere(['!=','type',2]);
        }
        // $view =ArticleInfo::find()->select('id')->andFilterWhere(['like','content','c.wedoctors.com.cn'])->column();

        //$articles->andFilterWhere(['not in','id',$view]);


        $pages = new Pagination(['totalCount' => $articles->count(), 'pageSize' => 10]);
        $list = $articles->orderBy('id desc')->offset($pages->offset)->limit($pages->limit)->all();

        foreach($list as $k=>$v)
        {
            $row=$v->info->toArray();
            $row['createtime']=date('Y/m/d',$v->createtime);
            $row['source']=$row['source']?$row['source']:"儿宝宝";
            $data['list'][]=$row;
        }
        $data['pageTotal']=ceil($articles->count()/10);

        return $data;
    }
    public function actionView($id){

    }

}