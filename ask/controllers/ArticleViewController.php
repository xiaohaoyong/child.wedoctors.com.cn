<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace ask\controllers;

use ask\controllers\Controller;

use common\models\Article;
use common\models\ArticleComment;
use common\models\ArticleLike;
use common\models\ArticleLog;
use common\models\ArticleUser;
use common\models\Carousel;
use common\models\WxInfo;
use yii\data\Pagination;
use yii\web\Response;

class ArticleViewController extends \yii\web\Controller
{
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
//        $row['isLike']=$like->andFilterWhere(['userid'=>$this->userid])->one()?1:0;
//
//        if (!ArticleLog::findOne(['userid' => $this->userid, 'artid' => $id])) {
//            $article_log = new ArticleLog();
//            $article_log->userid = $this->userid;
//            $article_log->artid = $id;
//            $article_log->save();
//        }
//        if ($article_user = ArticleUser::findOne(['touserid' => $this->userid, 'artid' => $id])) {
//            $article_user->level = 2;
//            $article_user->save();
//        }


        $comment=ArticleComment::find()->andFilterWhere(['artid'=>$id]);
        $pages = new Pagination(['totalCount' => $comment->count(), 'pageSize' => 10]);
        $list = $comment->orderBy('id desc')->offset($pages->offset)->limit($pages->limit)->all();
        foreach($list as $k=>$v){
            $rs=$v->toArray();
            $rs['createtime']=date('Y-m-d H:i',$v->createtime);
            $user=WxInfo::findOne(['userid'=>$v->userid]);
            $rs['user']=[];
            if($user) {
                $rs['user']=$user->toArray();
            }
            $data['list'][]=$rs;
        }
        $data['total']=$comment->count();

        return $this->renderPartial('view',[
            'article'=>$row,
            'comment'=>$data,
        ]);
    }
}