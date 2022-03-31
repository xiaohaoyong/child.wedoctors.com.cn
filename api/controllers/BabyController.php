<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/30
 * Time: 上午11:51
 */

namespace api\controllers;

use api\controllers\Controller;

use common\models\BabyGuide;
use common\models\BabyTool;
use common\models\BabyToolLike;
use common\models\BabyToolTag;
use common\models\Vaccine;

class BabyController extends Controller
{
    public function actionSex($period, $sex)
    {
        $tags = BabyToolTag::findOne($period);
        if ($sex == 2) {
            $tags->mother_num = $tags->mother_num + 1;
        } elseif ($sex == 1) {
            $tags->father_num = $tags->father_num + 1;
        }
        $tags->save();

    }

    public function actionTag()
    {

        $tags = BabyToolTag::find()->orderBy('week asc,id asc')->all();
        return $tags;
    }

    public function actionListNew($period)
    {

        $list = BabyTool::findAll(['period' => $period]);
        $nlist = BabyGuide::findAll(['period' => $period]);
        $like = BabyToolLike::findOne(['bid' => $period, 'loginid' => $this->userLogin->id, 'type' => 1]);
        $isCollection = BabyToolLike::findOne(['bid' => $period, 'loginid' => $this->userLogin->id, 'type' => 2]);
        $likeCount = BabyToolLike::find()->where(['bid' => $period, 'type' => 1])->count();

        $visit = BabyToolLike::find()->where(['bid' => $period, 'type' => 3, 'loginid' => $this->userLogin->id])->count();
        if (!$visit) {
            $visit = new BabyToolLike();
            $visit->bid = $period;
            $visit->userid = $this->userid;
            $visit->loginid = $this->userLogin->id;
            $visit->type = 3;
            $visit->save();
        }

        return ['list' => $list, 'nlist' => $nlist, 'isLike' => $like ? true : false, 'likeCount' => $likeCount, 'isCollection' => $isCollection ? true : false];
    }

    public function actionList($period)
    {
        $list = BabyTool::findAll(['period' => $period]);
        return $list;
    }

    public function actionVlist()
    {


        $list = Vaccine::find()->where(['>', 'source', 0])->orderBy('source asc')->all();
        return $list;
    }

    public function actionVview($id)
    {
        $view = Vaccine::findOne($id);
        return $view;
    }

    public function actionLike($id, $type)
    {
        $like = BabyToolLike::findOne(['bid' => $id, 'loginid' => $this->userLogin->id, 'type' => 1]);
        if ($like && $type == 2) {
            $like->delete();
        } elseif (!$like && $type == 1) {
            $like = new BabyToolLike();
            $like->bid = $id;
            $like->userid = $this->userid;
            $like->type = 1;
            $like->loginid = $this->userLogin->id;
            $like->save();
        }
    }

    public function actionCollection($id, $type)
    {
        $like = BabyToolLike::findOne(['bid' => $id, 'loginid' => $this->userLogin->id, 'type' => 2]);
        if ($like && $type == 2) {
            $like->delete();
        } elseif (!$like && $type == 1) {
            $like = new BabyToolLike();
            $like->bid = $id;
            $like->userid = $this->userid;
            $like->type = 2;
            $like->loginid = $this->userLogin->id;
            $like->save();
        }
    }
}