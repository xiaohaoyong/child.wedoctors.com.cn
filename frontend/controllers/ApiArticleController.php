<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/10/20
 * Time: 下午3:48
 */

namespace frontend\controllers;


use common\models\Article;
use common\models\ArticleCategory;
use common\models\ArticleLike;
use common\models\Points;
use common\models\UserDoctor;
use yii\data\Pagination;

class ApiArticleController extends ApiController
{
    public function actionCats(){
        $row['id']=0;
        $row['name']='推荐';
        $data[]=$row;
        $cat=ArticleCategory::find()->select('id,name')->andWhere(['pid'=>0])->orderBy('sort asc')->all();
        foreach($cat as $k=>$v){
            $row=$v->toArray();
            $rs=ArticleCategory::find()->select('id,name')->andWhere(['pid'=>$v->id])->all();
            $row['list']=[];
            foreach($rs as $rk=>$rv){
                $row['list'][]=$rv->toArray();
            }
            $data[]=$row;
        }
        return $data;
    }
    public function actionList($pid=0,$id=0){

        $articles=Article::find();
        $articles->andWhere(['>','`article`.level',0]);
        if(intval($pid))
        {
            $articles->andFilterWhere(['subject_pid'=>$pid]);
        }
        if(intval($id))
        {
            $articles->andFilterWhere(['subject'=>$id]);
        }
        if(!$pid && !$id)
        {
            $articles->andFilterWhere(['!=','catid',6]);
            $articles->andFilterWhere(['!=','type',2]);
            $articles->andFilterWhere(['!=','subject_pid',20]);

        }
        // $view =ArticleInfo::find()->select('id')->andFilterWhere(['like','content','c.wedoctors.com.cn'])->column();

        // $articles->andFilterWhere(['not in','id',$view]);


        $pages = new Pagination(['totalCount' => $articles->count(), 'pageSize' => 10]);
        $list = $articles->orderBy('id desc')->offset($pages->offset)->limit($pages->limit)->all();

        foreach($list as $k=>$v)
        {
            $row=$v->info->toArray();
            unset($row['content']);
            unset($row['video_url']);
            unset($row['ftitle']);

            $row['createtime']=date('Y/m/d',$v->createtime);
            $row['source']=$row['source']?$row['source']:"儿宝宝";
            $data['list'][]=$row;
        }
        $data['pageTotal']=ceil($articles->count()/10);

        return $data;
    }
    public function actionView($id){
        $article=Article::findOne($id);
        //$view =ArticleInfo::find()->select('id')->andFilterWhere(['like','content','c.wedoctors.com.cn'])->column();

        if(!$article) {

            $article=Article::findOne(313   );

        }
        $row=$article->info->toArray();
        $row['createtime']=date('Y-m-d',$row['createtime']);
        if($article->datauserid){
            $doctor=UserDoctor::findOne(['hospitalid'=>$article->datauserid]);
            $row['source']=$doctor->name;

        }else {
            $row['source'] = $row['source'] ? $row['source'] : "儿宝宝";
        }
        $like=ArticleLike::find()->andFilterWhere(['artid'=>$id]);
        $row['likeNum']=$like->count();

        return $row;
    }

}