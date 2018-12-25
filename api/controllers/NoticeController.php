<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 下午11:23
 */

namespace api\controllers;

use api\controllers\Controller;

use common\models\BabyTool;
use common\models\BabyToolLike;
use common\models\BabyToolTag;
use common\models\ChildInfo;
use common\models\Examination;
use common\models\HospitalAppoint;
use common\models\Notice;
use common\models\ArticleComment;
use common\models\UserDoctor;
use common\models\UserDoctorAppoint;
use common\models\UserParent;
use common\models\WxInfo;
use yii\data\Pagination;

class NoticeController extends Controller
{
    public function actionIndex()
    {

        //未登录，未注册 用户默认内容
        if (!$this->userid) {
            Notice::setList(0, 3, ['title' => '儿童中医药健康管理内容及平台服务', 'ftitle' => '点击查看服务内容', 'id' => '/article/view/index?id=200',]);
            //  Notice::setList($this->userid, 4, ['title' => '您好！医生给您发来了一份0-3岁儿童中医健康指导。', 'ftitle' => '0-3岁儿童中医健康知识', 'id' => '/article/guidance/index?t=0']);
        } else {
            $childs = ChildInfo::find()->select('id')->andFilterWhere(['userid' => $this->userid])->column();

            if ($childs) {
                $ex = Examination::find()->select('field52,childid')->andFilterWhere(['in', 'childid', $childs])->all();
                if($ex) {
                    foreach ($ex as $k => $v) {
                        $row[$v->childid] = $v->field52;
                    }
                    $exRow = array_filter($row, function ($arr) {
                        return $arr != '';
                    });
                    arsort($exRow);
                    if (array_values($exRow)[0] >= date('Y-m-d')) {

                        $exT['date'] = array_values($exRow)[0];
                        $exT['id'] = array_keys($exRow)[0];
                    }
                }
            }
            if ($exT) {
                $ex_data=[
                    'title' => '近期宝宝体检通知',
                    'ftitle' => $exT['date'] . ' (建议结合体检社区医院的门诊时间）',
                    'id' => '/user/examination/index?id=' . $exT['id'],
                    'temp'=>1
                ];
                $child=ChildInfo::findOne($exT['id']);
                if($child && $child->source) {
                    $doctor = UserDoctor::findOne(['hospitalid' => $child->source]);
                    if($doctor && $doctor->userid){
                        $appoint=HospitalAppoint::findOne(['doctorid'=>$doctor->userid,'type'=>1]);
                        if($appoint) {
                            $ex_data['id2'] = '/appoint/form?id=' . $doctor->userid . "&type=1";
                            $ex_data['temp']=3;
                        }
                    }
                }

                Notice::setList($this->userid, 1,$ex_data , "id=" . $exT['id']);
            }
        }

        if ($this->version && $this->version >= '0.9.4') {

            $childInfo = ChildInfo::find()->andWhere(['userid' => $this->userid])->orderBy('birthday asc')->one();
            $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $childInfo->birthday));
            $toolTag_tag=[];
            if($DiffDate[0]) {
                $toolTag_tag[0] = $DiffDate[0] . 'Y';
            }else{
                $d=ceil($DiffDate[2]/7);
                $toolTag_tag[2] = ($d==0?1:$d)."W";
            }
            if ($DiffDate[1] && $DiffDate[0]<3) {
                $toolTag_tag[1] = $DiffDate[1] . 'M';
            }elseif($DiffDate[0]>2){
                if($DiffDate[1]>=0 && $DiffDate[1]<3){

                }elseif ($DiffDate[1]>=3 && $DiffDate[1]<6){
                    $toolTag_tag[1] = '3M';
                }elseif ($DiffDate[1]>=6 && $DiffDate[1]<9){
                    $toolTag_tag[1] = '6M';
                }else{
                    $toolTag_tag[1] = '9M';
                }
            }

            ksort($toolTag_tag);
            $toolTag=implode("",$toolTag_tag);

            if ($tag = BabyToolTag::find()->where(['tag' => $toolTag])->one()) {
                $baby = BabyTool::findOne(['tag' => '宝宝', 'period' => $tag->id]);
                if ($tag->mother_num + $tag->father_num != 0) {
                    $mother_num = round($tag->mother_num / ($tag->mother_num + $tag->father_num), 2) * 100;
                    $father_num = round($tag->father_num / ($tag->mother_num + $tag->father_num), 2) * 100;
                } else {
                    $mother_num = 50;
                    $father_num = 50;
                }

                $like = BabyToolLike::find()->select('loginid')->where(['bid' => $tag->id])->andWhere(['type' => 3])->orderBy('id desc')->limit(2)->column();
                if($like) {
                    $userlist = WxInfo::find()->select('avatarUrl')->andFilterWhere(['in', 'loginid', $like])->column();
                }

                $likeCount = BabyToolLike::find()->where(['bid' => $tag->id])->andWhere(['type' => 3])->count();
                $isCollection=BabyToolLike::findOne(['bid'=> $tag->id,'loginid'=>$this->userLogin->id,'type'=>2]);

                Notice::setList($this->userid, 7, [
                    'userCount' => $likeCount,
                    'userlist' => $userlist,
                    'mother_num' => (int)$mother_num,
                    'father_num' => (int)$father_num,
                    'value' => $tag->name . "宝宝育儿指南",
                    'content' => mb_substr($baby->content, 0, 100, 'utf-8'),
                    'title' => $tag->name . "宝宝育儿指南",
                    'ftitle' => '家长必读',
                    'id' => '/tool/baby/index?catid=' . $tag->id,
                    'ids' => $tag->id,
                    'temp' => 2,
                    'isCollection'=>$isCollection?1:0,
                ], '', '/tool/baby/index');
            }
        }


        $userid = $this->userid ? $this->userid : 0;
        $list = Notice::getList($this->userid);
        foreach ($list as $k => $v) {
            $info = Notice::getRow($this->userid, $k);
            $rs['key'] = $k;
            $rs['name'] = Notice::$user[$k];
            $rs['date'] = $info['date'] ? $info['date'] : date('Y-m-d H:i', $v);
            $rs['info'] = $info;
            $data[] = $rs;
        }


        if (!$data) {
            Notice::setList($this->userid, 3, ['title' => '儿童中医药健康管理内容及平台服务', 'ftitle' => '点击查看服务内容', 'id' => '/article/view/index?id=200',]);
            $list = Notice::getList($this->userid);
            foreach ($list as $k => $v) {
                $rs['key'] = $k;
                $rs['name'] = Notice::$user[$k];
                $rs['date'] = date('Y-m-d H:i', $v);
                $rs['info'] = Notice::getRow($this->userid, $k);
                $data[] = $rs;
            }
        }
        return $data;

    }
}