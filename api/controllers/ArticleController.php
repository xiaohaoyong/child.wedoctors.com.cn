<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace api\controllers;

use api\models\Article;
use common\models\ArticleCategory;
use common\models\ArticleLike;
use common\models\ArticleLog;
use common\models\ArticleUser;
use common\models\Carousel;
use common\models\Points;
use yii\data\Pagination;

class ArticleController extends Controller
{
    public function actionIndex(){

        $cat=Article::$catText;
        $data['cat'][0]['key']=0;
        $data['cat'][0]['name']='推荐';
        $data['cat'][1]['key']=5;
        $data['cat'][1]['name']=$cat[5];
        $data['cat'][2]['key']=3;
        $data['cat'][2]['name']=$cat[3];
        $data['cat'][3]['key']=4;
        $data['cat'][3]['name']=$cat[4];
        $data['cat'][4]['key']=1;
        $data['cat'][4]['name']=$cat[1];
        $data['cat'][5]['key']=2;
        $data['cat'][5]['name']=$cat[2];


        $carousel=Carousel::find()->andFilterWhere(['type'=>1])->orderBy('sort desc ,id desc')->all();
        $data['imgs']=$carousel;

        return $data;
    }
    public function actionList($catid){
        $articles=Article::find();
        if(intval($catid))
        {
            $articles->andFilterWhere(['catid'=>$catid]);
        }else{
            $articles->andFilterWhere(['!=','catid',6]);
            $articles->andFilterWhere(['!=','type',2]);
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




    public function actionView($id){
        $article=Article::findOne($id);
        if(!$article) {

            $article=Article::findOne(301);

        }
        $row=$article->toArray();
        $row['createtime']=date('Y-m-d',$row['createtime']);
        $row['info']=$article->info->toArray();
        $row['info']['source']=$row['info']['source']?$row['info']['source']:"儿宝宝";

        $like=ArticleLike::find()->andFilterWhere(['artid'=>$id]);
        $row['likeNum']=$like->count();
        $row['isLike']=$like->andFilterWhere(['userid'=>$this->userid])->one()?1:0;

        if (!ArticleLog::findOne(['userid' => $this->userid, 'artid' => $id])) {
            $article_log = new ArticleLog();
            $article_log->userid = $this->userid;
            $article_log->artid = $id;
            $article_log->save();
        }
        $point=new Points();
        if ($article_user = ArticleUser::findOne(['touserid' => $this->userid, 'artid' => $id])) {
            $article_user->level = 2;
            $article_user->save();
            $point->addPoint($this->userid,1);
        }
        $point->addPoint($this->userid,3);



        return $row;
    }
    public function actionLike($artid){
        $like=ArticleLike::findOne(['artid'=>$artid,'userid'=>$this->userid]);
        if(!$like){
            $like=new ArticleLike();
            $like->userid=$this->userid;
            $like->artid=$artid;
            if($like->save()){
                $point=new Points();
                $point->addPoint($this->userid,7);
            }
        }
    }

    public function actionDelLike($artid){
        $like=ArticleLike::findOne(['artid'=>$artid,'userid'=>$this->userid]);
        if($like){
            $like->delete();
        }
    }

    /**
     * 用户未查看文章列表
     * @param int $page
     * @param null $type
     * @return string
     */
    public function actionRead($page = 1, $type = null)
    {
        $article=ArticleUser::find()->where(['touserid'=>$this->userid])->andFilterWhere(['level'=>$type]);

        $article->orderBy('createtime desc');
        //总共多少页
        $data['pageTotal'] = ceil($article->count() / 10);

        $pages = new Pagination(['totalCount' => $article->count(), 'pageSize' => 10]);
        $datas = $article->groupBy('artid')->offset($pages->offset)->limit($pages->limit)->all();

        if (!empty($datas)) {
            foreach ($datas as $key => $val) {
                $art = Article::findOne($val->artid);
                $row=$art->info->toArray();
                $row['createtime']=date('Y/m/d',$val->createtime);
                $row['source']=$row['source']?$row['source']:"儿宝宝";
                $data['list'][]=$row;
            }
        }
        return $data;

    }
}