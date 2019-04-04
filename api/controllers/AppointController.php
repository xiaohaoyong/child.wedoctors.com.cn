<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace api\controllers;

use common\components\Code;
use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\Article;
use common\models\ArticleLike;
use common\models\ArticleLog;
use common\models\ArticleUser;
use common\models\Carousel;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Doctors;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointWeek;
use common\models\UserDoctor;
use common\models\UserDoctorAppoint;
use databackend\models\User;
use dosamigos\qrcode\lib\Enum;
use dosamigos\qrcode\QrCode;
use linslin\yii2\curl\Curl;
use yii\data\Pagination;

class AppointController extends Controller
{
    public function actionDoctors($search=''){
        $query=UserDoctor::find();
        if($search){
            $query->andFilterWhere(['like','name',$search]);
        }
        $doctors=$query->orderBy('appoint desc')->all();

        $docs=[];

        $doctorParent=DoctorParent::findOne(['parentid'=>$this->userid]);
        if($doctorParent)
        {
            $doctorid=$doctorParent->doctorid;
        }

        foreach($doctors as $k=>$v){
            $rs=$v->toArray();
            $uda=UserDoctorAppoint::findOne(['doctorid'=>$v->userid]);
            $rs['name']=Hospital::findOne($v->hospitalid)->name;
            $docs[]=$rs;
            if($doctorid==$v->userid){
                $doc=$rs;
            }
        }




        return ['doctors'=>$docs,'doc'=>$doc];
    }

    public function actionDoctor($id){
        $uda=UserDoctor::findOne(['userid'=>$id]);
        $row=$uda->toArray();
        if($uda->appoint){
            $types=str_split((string)$uda->appoint);
        }
        $row['type1']=in_array(1,$types)?1:0;
        $row['type2']=in_array(2,$types)?1:0;
        $row['type3']=in_array(3,$types)?1:0;
        $hospital=Hospital::findOne($uda->hospitalid);

        $row['hospital']=$hospital->name;
        return $row;
    }

    public function actionForm($id,$type){
        $childs=ChildInfo::findAll(['userid'=>$this->userid]);


        //doctor
        $appoint=UserDoctorAppoint::findOne(['doctorid'=>$id,'type'=>$type]);
        if($appoint) {

            $phone = $this->userLogin->phone;
            $phone = $phone ? $phone : $this->user->phone;
            $row=$appoint->toArray();

            $holiday=[
                '2018-12-30',
                '2018-12-31',
                '2019-1-1',
                '2019-2-4',
                '2019-2-5',
                '2019-2-6',
                '2019-2-7',
                '2019-2-8',
                '2019-2-9',
                '2019-2-10',
                '2019-4-5',
                '2019-4-6',
                '2019-4-7',
                '2019-5-1',
                '2019-5-2',
                '2019-5-3',
                '2019-5-4',
                '2019-6-7',
                '2019-6-8',
                '2019-6-9',
                '2019-9-13',
                '2019-9-14',
                '2019-9-15',
                '2019-10-1',
                '2019-10-2',
                '2019-10-3',
                '2019-10-4',
                '2019-10-5',
                '2019-10-6',
                '2019-10-7',
            ];

            $appoints=Appoint::find()->select("count(*)")->indexBy('appoint_time')->where(['doctorid'=>$id,'type'=>$type])->groupBy('appoint_time')->column();
            $row['type1_num']=$row['type1_num']-$appoints[1]>=0?$row['type1_num']-$appoints[1]:0;
            $row['type2_num']=$row['type2_num']-$appoints[2]>=0?$row['type2_num']-$appoints[2]:0;
            $row['type3_num']=$row['type3_num']-$appoints[3]>=0?$row['type3_num']-$appoints[3]:0;
            $row['type4_num']=$row['type4_num']-$appoints[4]>=0?$row['type4_num']-$appoints[4]:0;
            $row['type5_num']=$row['type5_num']-$appoints[5]>=0?$row['type5_num']-$appoints[5]:0;
            $row['type6_num']=$row['type6_num']-$appoints[6]>=0?$row['type6_num']-$appoints[6]:0;

            return ['childs' => $childs, 'appoint' => $row, 'phone' => $phone,'holiday'=>$holiday];
        }else{
            return new Code(20010,'社区医院暂未开通服务！');
        }
    }
    public function actionDayNum($doctorid,$week,$type,$day){
        $rs=[];
        $appoint=HospitalAppoint::findOne(['doctorid'=>$doctorid,'type'=>$type]);
        if($appoint){
            $weeks=HospitalAppointWeek::find()->andWhere(['week'=>$week])->andWhere(['haid'=>$appoint->id])->orderBy('time_type asc')->all();
            if($weeks){
                $appoints=Appoint::find()
                    ->select('count(*)')
                    ->andWhere(['type'=>$type])
                    ->andWhere(['doctorid'=>$doctorid])
                    ->andWhere(['appoint_date'=>strtotime($day)])
                    ->andWhere(['!=','state',3])
                    ->andWhere(['mode'=>0])
                    ->indexBy('appoint_time')
                    ->groupBy('appoint_time')
                    ->column();
                foreach($weeks as $k=>$v){
                    if($appoints) {
                        $num = $v->num - $appoints[$v->time_type];
                        $rs[$v->time_type] = $num > 0 ? $num : 0;
                    }else{
                        $rs[$v->time_type] =$v->num ;
                    }
                }
                return $rs;
            }
        }
        return new Code(20020,'未设置');
    }

    public function actionSave(){
        $post=\Yii::$app->request->post();

        $appoint=Appoint::findOne(['childid'=>$post['childid'],'type'=>$post['type'],'state'=>1]);
        if($appoint){
            return new Code(20020,'您有未完成的预约');
        }elseif(!$post['childid']){
            return new Code(20020,'请选择宝宝');
        } else{
            $model = new Appoint();
            $post['appoint_date'] = strtotime($post['appoint_date']);
            $post['state'] = 1;
            $post['userid'] = $this->userid;
            $post['loginid']=$this->userLogin->id;
            $model->load(["Appoint" => $post]);
            if ($model->save()) {
                //var_dump($doctor->name);
                $userLogin=$this->userLogin;
                if($userLogin->openid) {
                    $doctor=UserDoctor::findOne(['userid'=>$model->doctorid]);
                    if($doctor){
                        $hospital=Hospital::findOne($doctor->hospitalid);
                    }
                    $child=ChildInfo::findOne($model->childid);

                    $data = [
                        'keyword1' => ARRAY('value' => Appoint::$typeText[$model->type]),
                        'keyword2' => ARRAY('value' => $hospital->name),
                        'keyword3' => ARRAY('value' => date('Y-m-d',$model->appoint_date)." ".Appoint::$timeText[$model->appoint_time]),
                        'keyword4' => ARRAY('value' => $child?$child->name:''),
                        'keyword5' => ARRAY('value' => $model->phone),
                        'keyword6' => ARRAY('value' => "预约成功"),
                        'keyword7' => ARRAY('value' => $model->createtime),
                        'keyword8' => ARRAY('value' => Appoint::$typeInfoText[$model->type]),
                    ];
                    $rs = WechatSendTmp::sendX($data,$userLogin->xopenid, 'Ejdm_Ih_W0Dyi6XrEV8Afrsg6HILZh0w8zg2uF0aIS0', '/pages/appoint/view?id='.$model->id,$post['formid']);
                }

                return ['id' => $model->id];
            } else {
                return new Code(20010, implode(':', $model->firstErrors));
            }
        }
    }

    public function actionView($id){
        $appoint=Appoint::findOne(['id'=>$id]);

        $row=$appoint->toArray();
        $doctor=UserDoctor::findOne(['userid'=>$appoint->doctorid]);
        if($doctor){
            $hospital=Hospital::findOne($doctor->hospitalid);
        }
        $row['hospital']=$hospital->name;
        $row['type']=Appoint::$typeText[$appoint->type];
        $row['time']=date('Y.m.d',$appoint->appoint_date)."  ".Appoint::$timeText[$appoint->appoint_time];
        $row['child_name']=ChildInfo::findOne($appoint->childid)->name;
        $row['duan']=$appoint->appoint_time;

        $index=Appoint::find()
            ->andWhere(['appoint_date'=>$appoint->appoint_date])
            ->andWhere(['<','id',$id])
            ->andWhere(['doctorid'=>$appoint->doctorid])
            ->andWhere(['appoint_time'=>$appoint->appoint_time])
            ->andWhere(['type' => $appoint->type])
            ->count();
        $row['index']=$index+1;
        return $row;
    }

    public function actionMy($state=1){
        if($state==1) {
            $appoints = Appoint::find()->andFilterWhere(['in','state',[1,5]])->andWhere(['userid' => $this->userid])->all();
        }else{
            $appoints = Appoint::findAll(['userid' => $this->userid, 'state' => $state]);
        }
        foreach($appoints as $k=>$v){
            $row=$v->toArray();
            $doctor=UserDoctor::findOne(['userid'=>$v->doctorid]);
            if($doctor){
                $hospital=Hospital::findOne($doctor->hospitalid);
            }
            $row['hospital']=$hospital->name;
            $row['type']=Appoint::$typeText[$v->type];
            $row['time']=date('Y.m.d',$v->appoint_date)."  ".Appoint::$timeText[$v->appoint_time];
            $row['stateText']=Appoint::$stateText[$v->state];
            $row['child_name']=ChildInfo::findOne($v->childid)->name;
            $list[]=$row;
        }
        return $list;
    }
    public function actionDelete($id,$formid){

        $model=Appoint::findOne(['id'=>$id,'userid'=>$this->userid]);
        if(!$model->delete()){
            return new Code(20010,'取消失败！');
        }else{
            $userLogin=$this->userLogin;
            if($userLogin->openid) {
                $doctor=UserDoctor::findOne(['userid'=>$model->doctorid]);
                if($doctor){
                    $hospital=Hospital::findOne($doctor->hospitalid);
                }
                $child=ChildInfo::findOne($model->childid);

                $data = [
                    'keyword1' => ARRAY('value' => Appoint::$typeText[$model->type]),
                    'keyword2' => ARRAY('value' => date('Y-m-d',$model->appoint_date)." ".Appoint::$timeText[$model->appoint_time]),
                    'keyword3' => ARRAY('value' => date('Y年m月d日 H:i:00')),
                    'keyword4' => ARRAY('value' => "已取消"),

                ];
                $rs = WechatSendTmp::sendX($data,$userLogin->xopenid, 'sG19zJw7LhBT-SrZYNJbuH1TTYtQFKfVEviXKf1ERFI','',$formid);
            }
        }
    }

    public function actionState($id,$formid,$type,$cancel_type=0){
        $model=Appoint::findOne(['id'=>$id,'userid'=>$this->userid]);
        if(!$model){
            return new Code(20010,'取消失败！');
        }else{

            if($type==1){
                $model->state=3;
                $model->cancel_type=$cancel_type;
            }elseif($type==2){
                $model->state=1;
            }
            $userLogin = $this->userLogin;
            if ($userLogin->openid) {
                $doctor = UserDoctor::findOne(['userid' => $model->doctorid]);
                if ($doctor) {
                    $hospital = Hospital::findOne($doctor->hospitalid);
                }
                $child = ChildInfo::findOne($model->childid);
            }
            if($model->save() && $userLogin->xopenid) {

                if($type==1) {
                    $data = [
                        'keyword1' => ARRAY('value' => Appoint::$typeText[$model->type]),
                        'keyword2' => ARRAY('value' => date('Y-m-d', $model->appoint_date) . " " . Appoint::$timeText[$model->appoint_time]),
                        'keyword3' => ARRAY('value' => date('Y年m月d日 H:i:00')),
                        'keyword4' => ARRAY('value' => "已取消"),
                    ];
                    $rs = WechatSendTmp::sendX($data, $userLogin->xopenid, 'sG19zJw7LhBT-SrZYNJbuH1TTYtQFKfVEviXKf1ERFI', '', $formid);

                }elseif($type==2){
                    $data = [
                        'keyword1' => ARRAY('value' => Appoint::$typeText[$model->type]),
                        'keyword2' => ARRAY('value' => $hospital->name),
                        'keyword3' => ARRAY('value' => date('Y-m-d',$model->appoint_date)." ".Appoint::$timeText[$model->appoint_time]),
                        'keyword4' => ARRAY('value' => $child?$child->name:''),
                        'keyword5' => ARRAY('value' => $model->phone),
                        'keyword6' => ARRAY('value' => "预约成功"),
                        'keyword7' => ARRAY('value' => $model->createtime),
                        'keyword8' => ARRAY('value' => Appoint::$typeInfoText[$model->type]),
                    ];
                    $rs = WechatSendTmp::sendX($data,$userLogin->xopenid, 'Ejdm_Ih_W0Dyi6XrEV8Afrsg6HILZh0w8zg2uF0aIS0', '/pages/appoint/view?id='.$model->id,$formid);
                }else{
                    return [];
                }
            }else{
                return new Code(20011,implode(',',$model->firstErrors));
            }
        }
    }

    public function actionQrCode($id){
        QrCode::png('appoint:'.$id,false,Enum::QR_ECLEVEL_H,10);exit;
    }


}