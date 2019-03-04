<?php
/**
 * 数据同步
 * User: wangzhen
 * Date: 2019/1/18
 * Time: 下午3:01
 */

namespace hospital\controllers;


use common\components\Log;
use common\models\DoctorParent;
use common\models\UserDoctor;
use common\models\UserParent;
use OSS\OssClient;

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

    public function actionData(){

        $ossClient = new OssClient('LTAIteFpOZnX3aoE', 'lYWI5AzSjQiZWBhC2d7Ttt06bnoDFF', 'oss-cn-beijing.aliyuncs.com');

        $row['accesskeyid']='LTAIteFpOZnX3aoE';
        $row['key']=110546;
        $row['policy']=base64_encode(json_encode([
            "expiration"=>date('Y-m-d\TH:i:s\Z', time()+(3600*24)),
            "conditions"=>[
                ['bucket'=>"wedoctorschild"],
                ["content-length-range", 0, 104857600]
            ],
        ]));
        $signature = base64_encode(hash_hmac('sha1', $row['policy'], 'lYWI5AzSjQiZWBhC2d7Ttt06bnoDFF', true));//生成认证签名
        $row['signature']=$signature;
        $callback=[
            'callbackUrl'=>'http://hospital.child.wedoctors.com.cn/synchronization/data-callback',
            'callbackBody'=>\Yii::$app->user->identity->hospitalid.'name='.date('Ymd'),
        ];
        $row['callback']=base64_encode(json_encode($callback));

        return $this->render('data',['row'=>$row]);
    }

    public function actionDataCallback(){
        $log=new Log('datacallback');
        $log->addLog(json_encode($_POST));
        $log->saveLog();
    }
}