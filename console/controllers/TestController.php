<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/8/22
 * Time: 上午9:47
 */

namespace console\controllers;


use common\models\ArticleUser;
use common\models\ChildInfo;
use common\models\DoctorParent;
use yii\base\Controller;

class TestController extends Controller
{
    public function actionCreateZip(){
        $zipname='./article-' . date("Ymd") . '.zip';

        //$zipname = dirname(__ROOT__)."/static/childEducation/".$filename;

        $zip = new \ZipArchive();
        $res = $zip->open($zipname, \ZipArchive::OVERWRITE | \ZipArchive::CREATE);
        $zip->addFile("data/110567.csv","110567.csv");
        $zip->close();

    }
    public function actionChildType(){


        $doctorParent= DoctorParent::find()->select('parentid')->where(['doctorid'=>80198])->andFilterWhere(['level'=>1])->column();

        $lmount=date('Y-m-01');
        //该类型 本月已发送的儿童
        $articleUser=ArticleUser::find()->select('touserid')
            ->where(['child_type'=>5])
            //->andFilterWhere(['>','createtime',strtotime($lmount)])
            ->groupBy('childid')
            ->column();

        $users=array_diff($doctorParent,$articleUser);
        if($doctorParent) {
            $mouth = ChildInfo::getChildType(5);
            //var_dump($mouth);exit;
            $childCount = ChildInfo::find()->select('userid')->where(['>=', 'birthday', $mouth['firstday']])->andFilterWhere(['<=', 'birthday', $mouth['lastday']])->andFilterWhere(['in', 'userid', array_values($users)])->column();
        }
        var_dump(implode(',',$childCount));exit;

    }
    public function actionEmail(){

        $mbox = imap_open ("{imap.163.com:993}INBOX", "etzyxm@163.com", "rowyJdD2MD");
        var_dump($mbox);exit;


    }

}