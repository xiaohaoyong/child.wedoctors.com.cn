<?php
/**
 * 数据同步
 * User: wangzhen
 * Date: 2019/1/18
 * Time: 下午3:01
 */

namespace hospital\controllers;


use common\models\DataUpdateRecord;
use common\components\Log;
use common\models\DoctorParent;
use common\models\UserDoctor;
use common\models\UserParent;
use OSS\OssClient;
use yii\web\Response;

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


        $row['accesskeyid']=\Yii::$app->params['aliak'];
        $row['key']=\Yii::$app->user->identity->hospitalid;
        $row['success_action_redirect']='http://hospital.child.wedoctors.com.cn/synchronization/';
        $row['success_action_status']=201;
        $row['policy']=base64_encode(json_encode([
            "expiration"=>date('Y-m-d\TH:i:s\Z', time()+(3600*24)),
            "conditions"=>[
                ['bucket'=>"wedoctorschild"],
                ["content-length-range", 0, 104857600]
            ],
        ]));
        $signature = base64_encode(hash_hmac('sha1', $row['policy'], \Yii::$app->params['aliaks'], true));//生成认证签名
        $row['signature']=$signature;
        $callback=[
            'callbackUrl'=>'http://hospital.child.wedoctors.com.cn/synchronization/data-callback',
            'callbackBody'=>'date='.date('Ymd').'&id='.\Yii::$app->user->identity->hospitalid,
        ];
        $row['callback']=base64_encode(json_encode($callback));

        $dur0=DataUpdateRecord::find()->where(['hospitalid'=>\Yii::$app->user->identity->hospitalid])->andWhere(['!=','state',3])->orderby('id desc')->all();
        $dur1=DataUpdateRecord::find()->where(['hospitalid'=>\Yii::$app->user->identity->hospitalid,'type'=>1])->orderby('id desc')->all();
        $dur2=DataUpdateRecord::find()->where(['hospitalid'=>\Yii::$app->user->identity->hospitalid,'type'=>2])->orderby('id desc')->all();
        $dur3=DataUpdateRecord::find()->where(['hospitalid'=>\Yii::$app->user->identity->hospitalid,'type'=>3])->orderby('id desc')->all();

        return $this->render('data',[
            'row'=>$row,
            'dur0'=>$dur0,
            'dur1'=>$dur1,
            'dur2'=>$dur2,
            'dur3'=>$dur3,
        ]);
    }

    public function actionDataCallback(){
        \Yii::$app->response->format=Response::FORMAT_JSON;

//        $log=new Log('datacallback');
//        $log->addLog(json_encode($_POST));
//        $log->saveLog();

        $dur=new DataUpdateRecord();
        $dur->hospitalid=$_POST['id'];
        $dur->save();


        $return = \Yii::$app->beanstalk
            ->putInTube('dataupdate', ['hospitalid'=>$_POST['id'],'date'=>$_POST['date'],'id'=>$dur->id]);
        return ['code'=>10000,'msg'=>'成功'];
    }
}