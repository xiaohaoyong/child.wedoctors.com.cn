<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace api\modules\v2\controllers;

use api\models\Article;
use common\models\ArticleCategory;
use common\models\Carousel;
use common\models\Points;
use yii\data\Pagination;

class ArticleController extends \api\modules\v1\controllers\ArticleController
{
    public function actionNewIndex(){

        $row['id']=0;
        $row['name']='推荐';
        $data['cat'][]=$row;
        $cat=ArticleCategory::find()->andWhere(['pid'=>0])->orderBy('sort asc')->all();
        foreach($cat as $k=>$v){
            $row=$v->toArray();
            $rs=ArticleCategory::find()->andWhere(['pid'=>$v->id])->all();
            $row['list']=[];
            foreach($rs as $rk=>$rv){
                $row['list'][]=$rv->toArray();
            }
            $data['cat'][]=$row;
        }

        $carousel=Carousel::find()->andFilterWhere(['type'=>1])->orderBy('sort desc ,id desc')->all();
        $data['imgs']=$carousel;

        return $data;
    }
    public function actionNewList($pid,$id){
        $articles=Article::find();
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
    public function actionPoint($id){
//        $point=new Points();
//        return $point->addPoint($this->userid,6,$id);
    }
}