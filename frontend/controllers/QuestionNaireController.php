<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/6/19
 * Time: 下午3:56
 */

namespace frontend\controllers;


use common\components\UploadForm;
use common\models\Appoint;
use common\models\QuestionNaire;
use common\models\QuestionNaireAnswer;
use common\models\QuestionNaireAsk;
use common\models\QuestionNaireField;
use OSS\OssClient;
use yii\web\Response;

class QuestionNaireController extends QnController
{
    public function actionForm($id,$doctorid=0)
    {
        $qnaa=QuestionNaireAnswer::find()->where(['qnid'=>$id,'userid'=>$this->login->userid])->orderBy('id desc')->one();
        if($qnaa && strtotime('+1 day',$qnaa->createtime) >time()){
            return $this->redirect(['question-naire/healthy','id'=>$id]);
        }
        $qnaa = new QuestionNaireAnswer();
        $qn = QuestionNaire::findOne($id);
        $qna = QuestionNaireAsk::findAll(['qnid' => $id]);
        $post=\Yii::$app->request->post()['QuestionNaireAnswer'];
        if ($post) {
            $qnf=new QuestionNaireField();
            $qnf->userid=$this->login->userid;
            $qnf->createtime=time();
            $qnf->qnid=$id;
            $transaction = \Yii::$app->db->beginTransaction();

            if($qnf->save()) {
                foreach ($post as $k => $v) {
                    foreach ($post[$k] as $pk => $pv) {
                        $qnaa = new QuestionNaireAnswer();
                        $qnaa->phone = 15811078604;
                        $qnaa->value = 'ccc';
                        $qnaa->idcode = '230107198908232610';
                        $qnaa->int = 1;
                        $qnaa->date = '2020-02-02';

                        $qnaa->answer = $pv;
                        $qnaa->doctorid = $doctorid;
                        $qnaa->qnaid = $pk;
                        $qnaa->qnid = $id;
                        $qnaa->qnfid=$qnf->id;
                        $qnaa->userid = $this->login->userid;
                        $qnaa->createtime = time();
                        if(!$qnaa->save()){
                            $transaction->rollBack();
                            return $this->render('form', [
                                'qn' => $qn,
                                'qna' => $qna,
                                'qnaa' => $qnaa
                            ]);
                        }
                    }
                }
                $transaction->commit();
            }
            return $this->redirect(['question-naire/sign','id'=>$qnf->id]);

        }
        return $this->render('form', [
            'qn' => $qn,
            'qna' => $qna,
            'qnaa' => $qnaa
        ]);
    }

    public function actionHealthy($id){

        if($id==3){

            $appoint=Appoint::findOne(['loginid'=>$this->login->id,'state'=>1,'type'=>9]);

        }



        $qnaa=QuestionNaireAnswer::find()->where(['qnid'=>$id,'userid'=>$this->login->userid])->orderBy('id desc')->one();
        if($id!=3 && time()>strtotime('+1 day',$qnaa->createtime))
        {
            return $this->redirect(['question-naire/form','id'=>$id,'doctorid'=>$qnaa->doctorid]);
        }
        if($id==3){

            $appoint=Appoint::findOne(['loginid'=>$this->login->id,'state'=>1,'type'=>9]);
            if(!$appoint){
                return $this->redirect(['naire-appoint/appoint','qid'=>$qnaa->qnfid,'doctorid'=>$qnaa->doctorid]);

            }
        }
        $qnaa1=QuestionNaireAnswer::findOne(['qnid'=>$id,'userid'=>$this->login->userid,'answer'=>1,'createtime'=>$qnaa->createtime]);

        if($qnaa1){
            $is_healthy=false;
        }else{
            $qnaa1=QuestionNaireAnswer::findOne(['qnid'=>$id,'userid'=>$this->login->userid,'createtime'=>$qnaa->createtime]);
            $is_healthy=true;
        }
        $qnaa2=QuestionNaireAnswer::findOne(['qnid'=>$id,'qnaid'=>22,'userid'=>$this->login->userid,'qnfid'=>$qnaa->qnfid]);
        if($qnaa2){
            $name=$qnaa2->answer;
        }

        return $this->render('healthy',[
            'is_healthy'=>$is_healthy,
            'qnaa'=>$qnaa1,
            'name'=>$name,
            'id'=>$id,
            'appoint'=>$appoint,
        ]);
    }
    public function actionView($id,$fid){
        $qnaa=QuestionNaireAnswer::find()->where(['qnid'=>$id,'userid'=>$this->login->userid,'qnfid'=>$fid])->indexBy('qnaid')->all();
      //  var_dump($qnaa);exit;
        $qn = QuestionNaire::findOne($id);
        $qna = QuestionNaireAsk::findAll(['qnid' => $id]);
        return $this->render('view', [
            'qn' => $qn,
            'qna' => $qna,
            'qnaa' => $qnaa
        ]);
    }
    public function actionNewView($id,$time,$userid){
        $qnaa=QuestionNaireAnswer::find()->where(['qnid'=>$id,'userid'=>$userid,'createtime'=>$time])->indexBy('qnaid')->all();
        $qn = QuestionNaire::findOne($id);
        $qna = QuestionNaireAsk::findAll(['qnid' => $id]);
        return $this->render('view1', [
            'qn' => $qn,
            'qna' => $qna,
            'qnaa' => $qnaa
        ]);
    }

    public function actionSign($id){
        $qnf=QuestionNaireField::findOne($id);
        if($qnf){




            return $this->render('sign',[
                'id'=>$id,
            ]);
        }else{
            return $this->redirect(['form']);
        }
    }
    public function actionSave($id){

        \Yii::$app->response->format = Response::FORMAT_JSON;
        $image_data = json_decode(file_get_contents('php://input'), true);
        if($image_data) {
            $qnf=QuestionNaireField::findOne($id);
            if ($qnf && $image_data) {
                $baseimage = base64_decode(rawurldecode($image_data['image_data']));
                $time = time();
                $filen = substr(md5($time . rand(10, 100)), 4, 14);
                $images = \Yii::$app->params['imageUrl'] . $filen . '.' . UploadForm::filetype2($baseimage);
                $ossClient = new OssClient('LTAIteFpOZnX3aoE', 'lYWI5AzSjQiZWBhC2d7Ttt06bnoDFF', 'oss-cn-qingdao.aliyuncs.com');
                $ossClient->putObject('childimage', 'upload/' . $filen . '.' . UploadForm::filetype2($baseimage), $baseimage);
                $qnf->sign = $images;
                $qnf->save();
            }
        }


        return ['code'=>10000,'msg'=>'成功','data'=>$qnf->qnid];
    }
}