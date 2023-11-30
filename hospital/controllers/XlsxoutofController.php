<?php

namespace hospital\controllers;


use common\models\DataUpdateRecord;
use common\components\Log;
use common\models\DoctorParent;
use common\models\UserDoctor;
use common\models\UserParent;
use OSS\OssClient;
use yii\web\Response;

use common\models\Xlsxoutof;
use common\models\XlsxoutofInfo;
use hospital\models\XlsxoutofInfoSearch;

class XlsxoutofController extends BaseController
{
    public function actionListExc()
    {
        
        
        $row=$row2=array();
        
        $row['accesskeyid']=\Yii::$app->params['aliak'];
        $row['key']=\Yii::$app->user->identity->hospitalid;
        $row['success_action_redirect']='http://test.hospital.child.wedoctors.com.cn/xlsxoutof/';
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
            'callbackUrl'=>'http://test.hospital.child.wedoctors.com.cn/xlsxoutof/data-callback/',
            'callbackBody'=>'date='.date('Ymd').'&type_num=0&id='.\Yii::$app->user->identity->hospitalid,        // 	0迁入1迁出 
        ];
        $row['callback']=base64_encode(json_encode($callback));
        
        
        $row2['accesskeyid']=\Yii::$app->params['aliak'];
        $row2['key']=\Yii::$app->user->identity->hospitalid;
        $row2['success_action_redirect']='http://test.hospital.child.wedoctors.com.cn/xlsxoutof/';
        $row2['success_action_status']=201;
        $row2['policy']=base64_encode(json_encode([
            "expiration"=>date('Y-m-d\TH:i:s\Z', time()+(3600*24)),
            "conditions"=>[
                ['bucket'=>"wedoctorschild"],
                ["content-length-range", 0, 104857600]
            ],
        ]));
        $signature = base64_encode(hash_hmac('sha1', $row2['policy'], \Yii::$app->params['aliaks'], true));//生成认证签名
        $row2['signature']=$signature;
        $callback=[
            'callbackUrl'=>'http://test.hospital.child.wedoctors.com.cn/xlsxoutof/data-callback/?',
            'callbackBody'=>'date='.date('Ymd').'&type_num=1&id='.\Yii::$app->user->identity->hospitalid,        // 	0迁入1迁出 
        ];
        $row2['callback']=base64_encode(json_encode($callback));
        
        
        
        
        $query = Xlsxoutof::find()
        ->where(['hospitalid'=>\Yii::$app->user->identity->hospitalid])
        ->orderBy('id desc')
        ->limit(20) ;
        
        $lists = $query->asArray()->all();
        
        $sql = $query->createCommand()->getRawSql();
        
        if( isset($_GET['debug']) )
        {
            var_dump(
            '$sql',$sql,
            '$lists',$lists
            );
        }
        
        return $this->render('list-exc',[
            
            'row'=>$row,'row2'=>$row2,
            
            'lists'=>$lists,
            
        ]);
    }
    
    
    
    public function actionDataCallback(){
        \Yii::$app->response->format=Response::FORMAT_JSON;

//        $log=new Log('datacallback');
//        $log->addLog(json_encode($_POST));
//        $log->saveLog();

        $Xlsxoutof=new Xlsxoutof();
        $Xlsxoutof->hospitalid=\Yii::$app->user->identity->hospitalid;
        $Xlsxoutof->type_num=$_POST['type_num'];
        $Xlsxoutof->add_time=time();
        $Xlsxoutof->save(false);

        $return = \Yii::$app->beanstalk
            ->putInTube('xlsxoutof', ['hospitalid'=>$_POST['id'],'date'=>$_POST['date'],'id'=>0,'Xlsxoutof_id'=>$Xlsxoutof->id]);
        return ['code'=>10000,'msg'=>'成功'];
    }
    
    
    
    
    
    
    public function actionListInfo()
    {
        $searchModel = new XlsxoutofInfoSearch();
        
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        
        //print_r($dataProvider);
        
        return $this->render('list-info', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        
    }
}