<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace frontend\controllers;


use common\models\Article;
use common\models\ArticleComment;
use common\models\ArticleLike;
use common\models\ArticleLog;
use common\models\ArticleUser;
use common\models\ChildInfo;
use common\models\User;
use common\models\UserLogin;
use common\models\UserParent;
use common\models\WxInfo;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleController extends Controller
{
    public $userid = 0;

    /**
     * 用户未查看文章列表
     * @param int $page
     * @param null $type
     * @return string
     */
    public function actionList($child_birthday = '', $child_name = '', $parent_name = '', $phone = '', $sign = "", $type = 0)
    {
        $phone = $phone ? $phone : 0;
//        if($sign!=md5($child_birthday.$child_name.$parent_name.$phone.'kLj2cJWQP4ebA6fB'.date('Ymd')))
//        {
//            return $this->renderPartial('list',[
//                'data'=>[],
//                'level'=>0,
//                'userid'=>0,
//            ]);
//        }
        $data['list'] = [];
        $data['pageTotal'] = 0;
        if ($phone) {
            if (preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
                $userLogin = UserLogin::findOne(['phone' => $phone]);
                if (!$userLogin) {
                    $user = User::findOne(['phone' => $phone]);

                    if (!$user) {
                        $userParent = UserParent::find()
                            ->Where(['mother_phone' => $phone])
                            ->Where(['father_phone' => $phone])
                            ->Where(['field12' => $phone])
                            ->one();
                        if ($userParent) {
                            $this->userid = $userParent->userid;
                        }
                    } else {
                        $this->userid = $user->id;
                    }
                } else {
                    $this->userid = $userLogin->userid;
                }
            }
        }

        if (!$this->userid && !$child_birthday && !$child_name) {
            $level = 1;
        } elseif (!$this->userid && $child_birthday && $child_name) {
            $user = new User();
            $user->phone = $phone ? $phone : 0;
            $user->type = 1;
            if ($user->save()) {
                $userLogin = new UserLogin();
                $userLogin->phone = $phone;
                $userLogin->userid = $user->id;
                $userLogin->type = 2;
                $userLogin->save();
                $this->userid = $user->id;

                $child = new ChildInfo();
                $child->userid = $this->userid;
                $child->name = $child_name;
                $child->birthday = strtotime($child_birthday);
                $child->source = 0;
                $child->save();
                if ($parent_name) {
                    $parent = new UserParent();
                    $parent->userid = $this->userid;
                    $parent->mother = $parent_name;
                    $parent->father = $parent_name;
                    $parent->save();
                }
            }
        }
        if ($this->userid) {
            $article = ArticleUser::find()->where(['touserid' => $this->userid])->andFilterWhere(['level' => $type]);

            $article->orderBy('createtime desc');
            //总共多少页
            $data['pageTotal'] = ceil($article->count() / 10);

            $pages = new Pagination(['totalCount' => $article->count(), 'pageSize' => 10]);
            $datas = $article->groupBy('artid')->offset($pages->offset)->limit($pages->limit)->all();
            if ($datas) {

                if (!empty($datas)) {
                    foreach ($datas as $key => $val) {
                        $art = Article::findOne($val->artid);
                        $row = $art->info->toArray();
                        $row['createtime'] = date('Y/m/d', $val->createtime);
                        $row['source'] = $row['source'] ? $row['source'] : "儿宝宝";
                        $data['list'][] = $row;
                    }
                }
            } elseif ($type == 0) {
                $articleid = ArticleUser::find()->select('artid')->where(['touserid' => $this->userid])->andFilterWhere(['level' => 2])->column();

                $article = Article::find()->where(['type' => 1]);
                if ($articleid) {
                    $article->andWhere(['not in', 'id', $articleid]);
                }
                //echo $article->createCommand()->getRawSql();exit;
                $list=$article->all();
                foreach ($list as $key => $val) {
                    $row = $val->info->toArray();
                    $row['createtime'] = date('Y/m/d');
                    $row['source'] = $val->source ? $val->source : "儿宝宝";
                    $data['list'][] = $row;
                }

            }
        }
        return $this->renderPartial('list', [
            'data' => $data,
            'level' => $level,
            'userid' => $this->userid,
        ]);
    }

    public function actionView($id, $userid = 0)
    {
        $article = Article::findOne($id);
        if (!$article) {

            $article = Article::findOne(301);

        }
        $row = $article->toArray();
        $row['createtime'] = date('Y-m-d', $row['createtime']);
        $row['info'] = $article->info->toArray();
        $row['info']['source'] = $row['info']['source'] ? $row['info']['source'] : "儿宝宝";

        $like = ArticleLike::find()->andFilterWhere(['artid' => $id]);
        $row['likeNum'] = $like->count();
        $row['isLike'] = $like->andFilterWhere(['userid' => $userid])->one() ? 1 : 0;

//        if (!ArticleLog::findOne(['userid' => $userid, 'artid' => $id])) {
//            $article_log = new ArticleLog();
//            $article_log->userid = $userid;
//            $article_log->artid = $id;
//            $article_log->save();
//            var_dump($article_log->firstErrors);exit;
//        }
        if ($article_user = ArticleUser::findOne(['touserid' => $userid, 'artid' => $id])) {
            $article_user->level = 2;
            $article_user->save();
        } elseif ($userid) {
            $article_user = new ArticleUser();
            $article_user->level = 2;
            $article_user->touserid = $userid;
            $article_user->artid = $id;
            $article_user->childid = 0;
            $article_user->userid = 0;
            $article_user->createtime = time();
            $article_user->save();
        }


        $comment=ArticleComment::find()->andFilterWhere(['artid'=>$id])->andWhere(['level'=>1]);
        $pages = new Pagination(['totalCount' => $comment->count(), 'pageSize' => 10]);
        $list = $comment->orderBy('id desc')->offset($pages->offset)->limit($pages->limit)->all();
        foreach ($list as $k => $v) {
            $rs = $v->toArray();
            $rs['createtime'] = date('Y-m-d H:i', $v->createtime);
            $user = WxInfo::findOne(['userid' => $v->userid]);
            $rs['user'] = [];
            if ($user) {
                $rs['user'] = $user->toArray();
            }
            $data['list'][] = $rs;
        }
        $data['total'] = $comment->count();

        return $this->renderPartial('view', [
            'article' => $row,
            'comment' => $data,
        ]);
    }


}