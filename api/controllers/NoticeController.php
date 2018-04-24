<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 下午11:23
 */

namespace api\controllers;


use common\models\ChildInfo;
use common\models\Examination;
use common\models\Notice;
use common\models\ArticleComment;
use common\models\UserParent;
use yii\data\Pagination;

class NoticeController extends Controller
{
    public function actionIndex(){


        //未登录，未注册 用户默认内容
        if(!$this->userid) {
            Notice::setList(0, 6, ['title' => '身高预测', 'ftitle' => '健康工具', 'id' => '/tool/height/index',]);
            Notice::setList(0, 3, ['title' => '儿童中医药健康管理内容及平台服务', 'ftitle' => '点击查看服务内容', 'id' => '/article/view/index?id=200',]);
            //  Notice::setList($this->userid, 4, ['title' => '您好！医生给您发来了一份0-3岁儿童中医健康指导。', 'ftitle' => '0-3岁儿童中医健康知识', 'id' => '/article/guidance/index?t=0']);
        }else{
            $childs = ChildInfo::find()->select('id')->andFilterWhere(['userid' => 928])->column();
            if ($childs) {
                $ex = Examination::find()->select('field52,childid')->andFilterWhere(['in', 'childid', $childs])->all();
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
            if ($exT) {
                Notice::setList($this->userid, 1, ['title' => '近期宝宝体检通知', 'ftitle' => $exT['date'].' (建议结合体检社区医院的门诊时间）', 'id' => '/user/examination/index?id='.$exT['id'],],"id=".$exT['id']);
            }
        }


        $userid=$this->userid?$this->userid:0;
        $list=Notice::getList($this->userid);
        foreach($list as $k=>$v)
        {
            $rs['key']=$k;
            $rs['name']=Notice::$user[$k];
            $rs['date']=date('Y-m-d H:i',$v);
            $rs['info']=Notice::getRow($this->userid,$k);
            $data[]=$rs;
        }
        if(!$data){
            Notice::setList($this->userid, 6, ['title' => '身高预测', 'ftitle' => '健康工具', 'id' => '/tool/height/index',]);
            Notice::setList($this->userid, 3, ['title' => '儿童中医药健康管理内容及平台服务', 'ftitle' => '点击查看服务内容', 'id' => '/article/view/index?id=200',]);

        }

        $list=Notice::getList($this->userid);
        foreach($list as $k=>$v)
        {
            $rs['key']=$k;
            $rs['name']=Notice::$user[$k];
            $rs['date']=date('Y-m-d H:i',$v);
            $rs['info']=Notice::getRow($this->userid,$k);
            $data[]=$rs;
        }
        return $data;

    }
}