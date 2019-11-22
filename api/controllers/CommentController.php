<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 下午11:23
 */

namespace api\controllers;

use api\controllers\Controller;

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
            $point->addPoint($this->userid,4);
        }
        return $comment->id;
    }
    public function actionList($id){
        
        $comment=ArticleComment::find()->andFilterWhere(['artid'=>$id]);
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