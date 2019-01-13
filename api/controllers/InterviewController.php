<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/1/8
 * Time: 下午2:27
 */

namespace api\controllers;


use common\components\Code;
use common\models\Interview;
use common\models\Pregnancy;

class InterviewController extends Controller
{
    public function actionSave($data){
        //查询当前用户末次月经
        $preg=Pregnancy::find()->where(['familyid'=>$this->userid])->andWhere(['>','source',0])->orderBy('field11 desc')->one();
        if($preg) {
            $week_type = Interview::getWeek($preg->field11);
        }
        $data['userid']=$this->userid;
        $data['week']=$week_type;
        $interview=new Interview();
        $interview->load($data);
        $interview->save();

        if($interview->firstErrors){
            return new Code(20010,implode(',',$interview->firstErrors));
        }
    }

}