<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/1/8
 * Time: 下午2:27
 */

namespace api\modules\v2\controllers;


use api\controllers\Controller;
use common\components\Code;
use common\models\BabyGuide;
use common\models\BabyToolTag;
use common\models\Interview;
use common\models\Notice;
use common\models\Pregnancy;

class InterviewController extends Controller
{
    public function actionSave()
    {
        $data = \Yii::$app->request->post();
        $data['userid'] = $this->userLogin->userid;
        if ($data['pt_date']) {
            $data['pt_date'] = strtotime($data['pt_date']);
        }
        if ($data['childbirth_date']) {
            $data['childbirth_date'] = strtotime($data['childbirth_date']);
        }
        $interview = new Interview();
        $interview->load(['Interview' => $data]);
        $interview->save();
        Notice::delKey($this->userid,8);

        if ($interview->firstErrors) {
            return new Code(20010, implode(',', $interview->firstErrors));
        }
    }

    public function actionList()
    {
        $preg = Pregnancy::find()->where(['familyid' => $this->userid])->orderBy('field11 desc')->one();
        $inter_week = Interview::$weekText;
        $getWeek = Interview::getWeek($preg->weeks);
        if ($preg) {
            $pregRow = $preg ? $preg->toArray() : [];
            $pregRow['field74'] = Pregnancy::$bmi[$preg->field74];
            $pregRow['week']="孕".$preg->weeks."周";
        }

        foreach ($inter_week as $k => $v) {
            if ($k <= $getWeek) {
                $inter = Interview::findOne(['userid' => $this->userid, 'week' => $k]);
                if ($inter && $inter->prenatal_test == 1) {
                    $row['prenatal_test'] = Interview::$prenatal_Text[$inter->prenatal_test];
                    $row['prenatal'] = $inter->prenatal_test ? Interview::$prenatalText[$inter->prenatal] : "无";
                    $row['pt_date'] = $inter->prenatal_test ? $inter->pdate : "无";
                    $row['pt_hospital'] = $inter->prenatal_test ? $inter->pt_hospital : "无";
                    $row['is_null'] = 0;
                } else {
                    $row['prenatal_test'] = "未完成";
                    $row['prenatal'] = "未完成";
                    $row['pt_date'] = "未完成";
                    $row['pt_hospital'] = "未完成";
                    $row['is_null'] = 1;
                }
                $row['weekid'] = $k;

                $byt = BabyToolTag::findOne(['tag' => Interview::$weekidText[$k] . "YW"]);
                $row['toolid'] = $byt ? $byt->id : 0;
                $row['week'] = Interview::$weekText[$k];
                $data[] = $row;
            }
        }
        return ['preg' => $pregRow, 'inter' => $data?array_reverse($data):[]];
    }

    public function actionRemind()
    {
        $preg = Pregnancy::find()->where(['familyid' => $this->userid])->orderBy('field11 desc')->one();
        if ($preg) {
            $btt = BabyToolTag::findOne(['tag' => '12YW']);
            $view = BabyGuide::findOne(['period' => $btt->id, 'title' => '准爸准妈要知道']);
            return $view;
        }
        return [];
    }
}