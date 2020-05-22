<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 下午11:23
 */

namespace api\controllers;

use api\controllers\Controller;

use common\models\Article;
use common\models\ArticleComment;
use common\models\Points;
use common\models\UserParent;
use common\models\WxInfo;
use yii\data\Pagination;

class CommentController extends Controller
{
    public function actionSend(){

        $content=\Yii::$app->request->post('content');
        $artid=\Yii::$app->request->post('artid');

        $comment=new ArticleComment();
        $comment->content=$content;
        $comment->userid=$this->userid;
        $comment->artid=$artid;
        $comment->save();
        if($comment){
            $point=new Points();
            $point->addPoint($this->userid,4,$artid);
        }
        return $comment->id;
    }
    public function actionList($id){

        $article=Article::findOne($id);
        $doctors=[110580];
        if(in_array($article->datauserid,$doctors)){
            $data['list']=[];
            $data['total']=0;
            return $data;
        }

        $comment=ArticleComment::find()->andFilterWhere(['artid'=>$id])->andWhere(['level'=>1]);
        $pages = new Pagination(['totalCount' => $comment->count(), 'pageSize' => 10]);
        $list = $comment->orderBy('id desc')->offset($pages->offset)->limit($pages->limit)->all();
        $data['list']=[];
        foreach($list as $k=>$v){
            $row=$v->toArray();
            $row['createtime']=date('Y-m-d H:i',$v->createtime);
            $user=WxInfo::findOne(['userid'=>$v->userid]);
            $row['user']=[];
            if($user) {
                $row['user']=$user->toArray();
            }
            $data['list'][]=$row;
        }
        $data['total']=$comment->count();

        return $data;

    }

}