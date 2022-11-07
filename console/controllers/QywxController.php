<?php
/**
 * Created by PhpStorm.
 * User: zhangzhenyu
 * Date: 2022/10/10
 * Time: 下午1:05
 */

namespace console\controllers;

use common\models\LoginTag;
use common\models\QywxUseridRelation;
use common\models\UserTag;
use common\models\UserType;
use EasyWeChat\Factory;
use yii\web\Controller;


const DS  = DIRECTORY_SEPARATOR;

class QywxController extends \yii\console\Controller
{

    public static $config = [
        // 企业的id，在管理端->"我的企业" 可以看到
        "CORP_ID"               => "ww3aa86f4dcc2a55f5",

        // "通讯录同步"应用的secret, 开启api接口同步后，可以在管理端->"通讯录同步"看到
        "CONTACT_SYNC_SECRET"   => "35D4JgYfsvgxebSX9vjfvbOl-_6MbpAqZKO-LxXHUAM",

        //客户联系（外部联系人）
        "EXT_CONTACT_SYNC_SECRET"   => "0UOqneeDS5SZzTk05JhVk24Z3GiRw32BDa4NCpd_4_8",
    ];

    /**
     * 需要拉取的企业微信客服id列表
     * @var array
     */
    public static $wx_cs_list = [
        'BuHuiFeiDeShaNiao',
        'TingBao'
    ];


    /**
     * 拉取指定微信客服的客户，同步到数据库
     */
    public function actionPull(){
        //var_dump(\Yii::$app->params['qywx']);exit;
        $app = Factory::work(\Yii::$app->params['qywx']);

        //$api = new \CorpAPI(self::$config['CORP_ID'], self::$config['EXT_CONTACT_SYNC_SECRET']);

        $i=1;
        $step = 100;  #处理多少条休息一次
        echo PHP_EOL.'###########################'.PHP_EOL;
        echo 'Start time:'.date('Y-m-d H:i:s',time());
        foreach(self::$wx_cs_list as $cs_id)
        {
            $count_success = 0;
            $count_failed = 0;
//
//            $userList = $api->ExtUserList($cs_id);
//            $external_userids = $userList['external_userid'];
//            $userId = 'zhangsan';

            $userList = $app->external_contact->list($cs_id);
            $external_userids = $userList['external_userid'];
            $userId = 'zhangsan';
            foreach ($external_userids as $ext_userid)
            {

                if ($i%$step == 0)
                    sleep(1);

                $uinfo = $app->external_contact->get($ext_userid);
                $model = new QywxUseridRelation();

                if (isset($uinfo['external_contact']['unionid'])) {
                    $result = $model->refreshByUnionid($uinfo['external_contact']['unionid'], $uinfo['external_contact']['external_userid']);
                    if ($result) {
                        $count_success += 1;
                    } else {
                        $count_failed += 1;
                    }
                }
                else{
                    $count_failed += 1;
                }
                $i++;

            }

            echo PHP_EOL.'###########################'.PHP_EOL;
            echo 'cs:'.$cs_id.' success count:'.$count_success.'; failed count:'.$count_failed.PHP_EOL;
        }

        echo PHP_EOL.'###########################'.PHP_EOL;
        echo 'End time:'.date('Y-m-d H:i:s',time());
        exit;

    }

    public function actionUpdateTag(){
        $qy=QywxUseridRelation::find()->all();
        foreach($qy as $k=>$v){
            UserType::action($v->unionid,$v);
        }
    }
    /**
     * 获取本企业所有客服id
     */
    public function actionCustomers()
    {
        //获取指定企业的所有员工用户
        // 需启用 "管理工具" -> "通讯录同步", 并使用该处的secret, 才能通过API管理通讯录

        $api = new \CorpAPI(self::$config['CORP_ID'], self::$config['CONTACT_SYNC_SECRET']);
        $userList = $api->UserList(1, 0);
        var_dump($userList);
        exit;
    }

    /**
     * 保存指定客户的标签数据到企业微信
     */
    public function actionSavetag()
    {
        $api = new \CorpAPI(self::$config['CORP_ID'], self::$config['EXT_CONTACT_SYNC_SECRET']);

        @$result  = $api->ExtUserTagSave('BuHuiFeiDeShaNiao','wmCPM_CQAALpACo2l1QBMZErCsEk5qhg',['etCPM_CQAAsTiUAy5tmpkmtsLlJOdZvA'],[]);
        @$result  = $api->ExtUserTagSave('BuHuiFeiDeShaNiao','wmCPM_CQAAQZDAPB35oDB_3zCGLNpMKg',['etCPM_CQAAHPi_tTonFyuN2IzEgSOBSg','etCPM_CQAAmcBxybq6JiTV6bVDS3V96w'],[]);

        var_dump($result);
        exit;

    }

    /**
     * 获取企业所有客户标签信息
     */
    public function actionAlltag()
    {
//        // 需启用 "管理工具" -> "通讯录同步", 并使用该处的secret, 才能通过API管理通讯录
//        $api = new \CorpAPI(self::$config['CORP_ID'], self::$config['EXT_CONTACT_SYNC_SECRET']);
//
//        $tagList = $api->GorpTagGetList();
//        var_dump($tagList);
//        exit;
        $app = Factory::work(\Yii::$app->params['qywx']);
        $tags=$app->external_contact->getCorpTags();
        var_dump($tags);exit;
        return [];

    }

    public function actionUpdateWxTag(){
        $qywx=QywxUseridRelation::find()->all();
        $app = Factory::work(\Yii::$app->params['qywx']);

        foreach($qywx as $k=>$v){
            if($v->login_id)
            {
                $add_tag = LoginTag::find()->select('tagid')->where(['loginid'=>$v->login_id])->andWhere(['level'=>1])->column();
                $remove_tag = LoginTag::find()->select('tagid')->where(['loginid'=>$v->login_id])->andWhere(['level'=>0])->column();
                $addText = UserTag::find()->select('wx_tag_id')->where(['!=','wx_tag_id',''])->andWhere(['in','id',$add_tag])->column();
                $removeText = UserTag::find()->select('wx_tag_id')->where(['!=','wx_tag_id',''])->andWhere(['in','id',$remove_tag])->column();
                if($addText || $removeText) {
                    var_dump($addText);
                    var_dump($removeText);

                    $params = [
                        "userid" => "TingBao",
                        "external_userid" => $v->external_userid,
                        "add_tag" => $addText ? $addText : [],
                        "remove_tag" => $removeText ? $removeText : []
                    ];

                    $return = $app->external_contact->markTags($params);
                    var_dump($return);
                }
            }

        }


    }

}