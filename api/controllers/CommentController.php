<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 下午11:23
 */

namespace api\controllers;


use common\models\ArticleComment;
use common\models\UserParent;
<<<<<<< HEAD
use common\models\WxInfo;
=======
>>>>>>> eabc1625d436a17f2766a1bc9c0c48efafe4622e
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
        return $comment->id;
    }
    public function actionList($id){
<<<<<<< HEAD
        
=======
>>>>>>> eabc1625d436a17f2766a1bc9c0c48efafe4622e
        $comment=ArticleComment::find()->andFilterWhere(['artid'=>$id]);
        $pages = new Pagination(['totalCount' => $comment->count(), 'pageSize' => 10]);
        $list = $comment->orderBy('id desc')->offset($pages->offset)->limit($pages->limit)->all();
        foreach($list as $k=>$v){
            $row=$v->toArray();
            $row['createtime']=date('Y-m-s H:i',$v->createtime);
<<<<<<< HEAD
            $user=WxInfo::findOne(['userid'=>$v->userid]);
            $row['user']=[];
            if($user) {
                $row['user']=$user->toArray();
            }
=======
            $row['user']=UserParent::findOne($v->userid)->toArray();
>>>>>>> eabc1625d436a17f2766a1bc9c0c48efafe4622e
            $data['list'][]=$row;
        }
        $data['total']=$comment->count();

        return $data;

    }

}