<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/18
 * Time: 下午2:43
 */

namespace api\controllers;

use api\controllers\Controller;

use common\helpers\HuanxinUserHelper;
use common\models\Area;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\DoctorTeam;
use common\models\Hospital;
use common\models\Street;
use common\models\UserDoctor;
use common\models\UserLogin;
use databackend\models\article\Article;

class DoctorController extends Controller
{
    public function actionView()
    {
        $doctor = '';
        $doctorParent = DoctorParent::findOne(['parentid' => $this->userid]);
        if ($doctorParent) {

            $doctor = UserDoctor::findOne(['userid' => $doctorParent->doctorid]);

        }
        $child = ChildInfo::findOne(['userid'=>$this->userid]);
        if ($child->teamid) {
            $doctorTeam = DoctorTeam::findOne($child->teamid);
        }


        $articles = Article::find()->where(['datauserid' => $doctor->hospitalid])->orderBy('id desc')->all();
        $data = [];
        foreach ($articles as $k => $v) {
            if ($v->info) {
                $row = $v->info->toArray();
                $row['title'] = mb_substr($row['title'], 0, 19, 'utf-8');
                $row['createtime'] = date('m/d', $v->createtime);
                $row['source'] = $row['source'] ? $row['source'] : "儿宝宝";
                $data[] = $row;
            }
        }

        $area = ['请选择']+array_values(Area::$county[11]);
        $hospitals = UserDoctor::find()->where(['city' => 11])->all();
        foreach ($hospitals as $k => $v) {
            $hospitals_rs[] = $v->hospital->name;
            $hospitals_name[Area::$all[$v->county]][]=$v->hospital->name;
        }
//        $huanxin = md5($doctorParent->doctorid.'7Z9WL3s2');
//        HuanxinUserHelper::getUserInfo($huanxin);
//        $userlogin=UserLogin::findOne(['userid'=>$doctorParent->doctorid]);
//        //$userlogin->hxusername=$huanxin;
//        $userlogin->save();
        return ['doctor' => $doctor, 'list' => $data, 'username' => $huanxin, 'team' => $doctorTeam, 'areas' => $area, 'hospitals' => $hospitals_rs,'hospitals_name'=>$hospitals_name];
    }

    public function actionRow($doctorid)
    {
        if (!$doctorid) {
            $doctorParent = DoctorParent::findOne(['parentid' => $this->userid]);
            $doctorid = $doctorParent->doctorid;
        }
        $doctor = '';
        $doctor = UserDoctor::findOne(['userid' => $doctorid]);
        $street = Street::find()->select('title')->where(['doctorid' => $doctorid])->column();

        return ['doctor' => $doctor, 'street' => $street];
    }

}