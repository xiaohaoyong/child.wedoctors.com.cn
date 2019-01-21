<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/1/18
 * Time: 下午3:01
 */

namespace hospital\controllers;


use common\models\DoctorParent;
use common\models\UserDoctor;
use common\models\UserParent;

class SynchronizationController extends BaseController
{
    public function actionIndex()
    {
        $post = \Yii::$app->request->post();
        $names=[];
        if ($post['idx']) {
            $idxs = explode("\n", $post['idx']);
            $doctor=UserDoctor::findOne(['hospitalid'=>\Yii::$app->user->identity->hospitalid]);
            foreach ($idxs as $k => $v) {
                if (trim($v)) {
                    $userParent = UserParent::findOne(['mother_id' => trim($v),'source'=>\Yii::$app->user->identity->hospitalid]);
                    if ($userParent) {
                        $doctorParent=DoctorParent::findOne(['parentid'=>$userParent->userid]);
                        if(!$doctorParent){
                            $doctorParent=new DoctorParent();
                            $doctorParent->doctorid=$doctor->userid;
                            $doctorParent->parentid=$userParent->userid;
                            $doctorParent->level=1;
                            $doctorParent->save();
                            $names[] = trim($v)."==".$userParent->mother."==签约成功";
                        }else{
                            $names[] = trim($v)."==".$userParent->mother."==已签约";
                        }
                    } else
                        $names[] = '--未查询到--';
                } else {
                    $names[] = '--';
                }
            }
        }
        return $this->render('index',[
            'names'=>implode("\n",$names),
            'idx'=>$post['idx']
        ]);
    }
}