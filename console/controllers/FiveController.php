<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/11/23
 * Time: 下午7:42
 */

namespace console\controllers;


use common\models\Access;
use common\models\Appoint;
use common\models\Area;
use common\models\Autograph;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Hospital;
use common\models\TmpLog;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\UserParent;
use console\models\Pregnancy;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\console\Controller;

class FiveController extends Controller
{
    public function actionUpdateData()
    {
        ini_set('memory_limit','4096M');

        $access=Access::find()
            ->where(['doctorid'=>0])
            ->andWhere(['>','createtime',1736784000])
            ->andWhere(['month'=>0])
            ->andWhere(['type'=>1])
            ->all();
        foreach($access as $k=>$v){
            echo $v->cid."\n";
            $appoint=Appoint::findOne($v->cid);
            $child=ChildInfo::findOne($appoint->childid);
            if($child) {
                $day=ceil(($v->createtime-$child->birthday)/86400);
                echo $day;
                $v->month = $day;
            }
            $v->doctorid = $appoint->doctorid;
            $v->save();
            var_dump($v->firstErrors);

            echo "\n";
        }
//        $tmp=TmpLog::find()->where(['doctorid'=>0])->andWhere(['fid'=>1985])->all();
//        foreach($tmp as $k=>$v){
//            $userLogin=UserLogin::findOne(['openid'=>$v->openid]);
//            $doctorParent=DoctorParent::findOne(['parentid'=>$userLogin->userid]);
//            $v->doctorid=$doctorParent->doctorid;
//            $child=ChildInfo::find()->where(['userid'=>$userLogin->userid])->orderBy('birthday asc')->one();
//            if($child) {
//                $day=ceil(($v->createtime-$child->birthday)/86400);
//                echo $day;echo "\n";
//                $v->day = $day;
//            }
//            $v->save();
//        }
    }

    public function actionExcel()
    {
        $stime=strtotime('2025-01-14');
        $etime=strtotime('2025-03-24');
        $userDoctor = UserDoctor::find()->groupBy('county')->all();
        foreach ($userDoctor as $k=>$v){
            $rs=[];
            $doctorids = UserDoctor::find()->select('userid')->where(['county'=>$v->county])->column();
            $doctorParent=DoctorParent::find()->select('parentid')
                ->leftJoin('child_info', '`child_info`.`userid` = `doctor_parent`.`parentid`')
                ->where(['doctor_parent.doctorid'=>$doctorids])
                ->andWhere(['>','child_info.birthday',strtotime('2024-04-01')])
                ->count();
            //$child=ChildInfo::find()->where(['userid'=>$doctorParent])->where(['>','birthday',strtotime('2024-04-01')])->count();
//            $c=0;
//            foreach($child as $ck=>$cv){
//                $dp=DoctorParent::find()->where(['parentid'=>$cv->userid])->one();
//                if(ceil(($dp->createtime-$cv->birthday)/86400)<30)
//                {
//                    $c++;
//                }
//            }
            //$rs[]=$doctorids;

            $rs[]=Area::$all[$v->county];

            $rs[]=$doctorParent;
//            //推送总人数（小于74天的）
//            $tmpLog=TmpLog::find()
//                ->select('openid')
//                ->where(['>','createtime',$stime])
//                ->andWhere(['<','createtime',$etime])
//                ->andWhere(['doctorid'=>$doctorids])
//                ->andWhere(['fid'=>1985])
//                ->andWhere(['>=','day',0])
//                ->andWhere(['<=','day',30])
//                ->column();
//            $rs[]=count($tmpLog);
//            //打开人数(小于74天的)
//
//            $userids=UserLogin::find()->select('userid')->where(['in','openid',$tmpLog])->column();
//            $acc=Access::find()
//                ->select('userid')
//                ->where(['>','createtime',$stime])
//                ->andWhere(['<','createtime',$etime])
//                ->andWhere(['doctorid'=>$doctorids])
//                ->andWhere(['in','userid',$userids])
//                ->andWhere(['cid'=>1985])
//                ->column();
//            $acc=array_unique($acc);
//            $rs[]=count($acc);
            //停留10秒以上的人数

//            $acc=Access::find()
//                ->select('userid')
//                ->where(['>','createtime',$stime])
//                ->andWhere(['<','createtime',$etime])
//                ->andWhere(['doctorid'=>$doctorids])
//                ->andWhere(['in','userid',$userids])
//                ->andWhere(['>','long',9])
//                ->andWhere(['cid'=>1985])
//                ->column();
//            $acc=array_unique($acc);
//            $rs[]=count($acc);
            //预约弹窗总人数（小于74天）
            $acc=Access::find()
                ->select('userid')
                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['doctorid'=>$doctorids])
//                ->andWhere(['>=','month',0])
//                ->andWhere(['<=','month',30])
                ->andWhere(['type'=>1])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);

            $acc=Access::find()
                ->select('userid')
                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['doctorid'=>$doctorids])
//                ->andWhere(['>=','month',0])
//                ->andWhere(['<=','month',30])
                ->andWhere(['type'=>1])
                ->andWhere(['>','long',10])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);


            //推送总人数（小于74天的）
            $tmpLog=TmpLog::find()
                ->select('openid')
                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['doctorid'=>$doctorids])
                ->andWhere(['fid'=>1985])
                ->andWhere(['>=','day',31])
                ->andWhere(['<=','day',60])
                ->column();
            $rs[]=count($tmpLog);
            //打开人数(小于74天的)

            $userids=UserLogin::find()->select('userid')->where(['in','openid',$tmpLog])->column();
            $acc=Access::find()
                ->select('userid')
                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['doctorid'=>$doctorids])
                ->andWhere(['in','userid',$userids])
                ->andWhere(['cid'=>1985])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
            //停留10秒以上的人数

            $acc=Access::find()
                ->select('userid')
                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['doctorid'=>$doctorids])
                ->andWhere(['in','userid',$userids])
                ->andWhere(['>','long',9])
                ->andWhere(['cid'=>1985])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
            //预约弹窗总人数（小于74天）
            $acc=Access::find()
                ->select('userid')
                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['doctorid'=>$doctorids])
                ->andWhere(['>=','month',31])
                ->andWhere(['<=','month',60])
                ->andWhere(['type'=>1])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
//预约弹窗总人数（小于74天）
            $acc=Access::find()
                ->select('userid')
                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['doctorid'=>$doctorids])
                ->andWhere(['>=','month',31])
                ->andWhere(['<=','month',60])
                ->andWhere(['type'=>1])
                ->andWhere(['>','long',9])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);




            //首页视频打开数
            $acc=Access::find()
                ->select('userid')
                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['doctorid'=>$doctorids])
                ->andWhere(['cid'=>1371])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
            //首页视频停留到播放20秒的人数（目前无五联内容）
            $acc=Access::find()
                ->select('userid')

                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['doctorid'=>$doctorids])
                ->andWhere(['cid'=>1371])
                ->andWhere(['>','long',20])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
            //五联视频链接的点开人数
            $acc=Access::find()
                ->select('userid')

                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['doctorid'=>$doctorids])
                ->andWhere(['type'=>4])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
            //视频停留30秒以上的人数
            $acc=Access::find()
                ->select('userid')

                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['doctorid'=>$doctorids])
                ->andWhere(['cid'=>1983])
                ->andWhere(['>','long',30])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
            //视频停留15秒以上的人数
            $acc=Access::find()
                ->select('userid')

                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['doctorid'=>$doctorids])
                ->andWhere(['cid'=>1983])
                ->andWhere(['>','long',15])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
            echo implode(',',$rs);
            echo "\n";
        }
    }
}