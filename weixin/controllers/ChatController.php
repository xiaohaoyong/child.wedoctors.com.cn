<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 17/11/21
 * Time: 下午2:51
 */

namespace weixin\controllers;


use backend\models\UserDoctorSearchModel;
use callmez\wechat\sdk\MpWechat;
use common\base\BaseWeixinController;
use common\components\UploadForm;
use common\helpers\WechatSendTmp;
use common\models\ChatRecord;
use common\models\ChatRecordContent;
use common\models\Message;
use weixin\models\ChildInfo;
use weixin\models\User;
use weixin\models\UserDoctor;
use weixin\models\UserLogin;
use weixin\models\UserParent;
use yii\helpers\Url;
use yii\web\Controller;

class ChatController extends BaseWeixinController
{
    public function actionUserChat($userid,$touserid)
    {
        $userid=$this->userData['userid'];
        $ChatRecord=ChatRecord::find()->where(['in','userid',[$userid,$touserid]])
            ->andFilterWhere(['in','touserid',[$userid,$touserid]])
            ->orderBy('id')->limit(100)->all();
        $chat=[];
        if($ChatRecord)
        {
            foreach($ChatRecord as $k=>$v)
            {
                $row=[];
                if($v->userid==$userid)
                {
                    $row['userid']=ChatRecordContent::findOne(['chatid'=>$v->id])->content;
                }else{
                    $row['touserid']=ChatRecordContent::findOne(['chatid'=>$v->id])->content;
                }
                $chat[]=$row;
            }
        }

        ChatRecord::updateAll(['read'=>1],['touserid'=>$userid,'userid'=>$touserid]);
        return $this->returnJson('200', 'success', $chat);
    }

    public function actionAdd()
    {
        $userid=$this->userData['userid'];
        $touserid=\Yii::$app->request->post('touserid');
        $content=\Yii::$app->request->post('content');
        $type=\Yii::$app->request->post('type');
        if($type==1) {
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $content, $result)) {
                $type = $result[2];
                $time = time();
                $filename = substr(md5($time), 4, 14).".".$type;
                if (file_put_contents(__ROOT__."/../../".\Yii::$app->params['imageDir']."/upload/".$filename, base64_decode(str_replace($result[1], '', $content)))) {
                    $content = '[img]'.\Yii::$app->params['imageUrl'].$filename;

                }
            }
        }
        $chat=new ChatRecord();
        $chat->userid=$userid;
        $chat->touserid=$touserid;
        $chat->createtime=time();
        if($chat->save())
        {
            $chatContent=new ChatRecordContent();
            $chatContent->content=$content;
            $chatContent->chatid=$chat->id;
            if($chatContent->save())
            {
                if(User::findOne($userid)->type==1)
                {
                    $chat=new ChatRecord();
                    $chat->userid=$touserid;
                    $chat->touserid=$userid;
                    $chat->createtime=time();
                    if($chat->save())
                    {
                        $chatContent=new ChatRecordContent();
                        $userDoctor=UserDoctor::findOne($touserid);
                        $chatContent->content="对不起，当前医生不在线，请拨打电话咨询。\n ".$userDoctor->name.'电话：'.$userDoctor->phone;
                        $chatContent->chatid=$chat->id;
                        $chatContent->save();
                    }
                }


                sleep(2);
                $chatRead=ChatRecord::findOne($chat->id)->read;
                if(!$chatRead)
                {
                    $data['touser']=UserLogin::findOne(['userid'=>$touserid])->openid;
                    $data['msgtype']="text";

                    if(User::findOne($userid)->type==1)
                    {
                        $child=ChildInfo::findOne(['userid'=>$userid]);
                        $name=$child->name."等儿童的家长";
                        $url = \Yii::$app->params['site_url'].'#/talk-fordoctor?childid='.$userid."&childname=".urlencode($name);


                    }else{
                        $user=UserDoctor::findOne($userid);
                        $name=$user->name;
                        $url = \Yii::$app->params['site_url'].'#/talk-forparent?touserid='.$userid."&doctor=".urlencode($user->name);
                    }
                    $data['text']['content']=$name.":".$content."\n<a href='{$url}'>点击查看</a>";
                    WechatSendTmp::sendMessage($data);
                }
            }
            return $this->returnJson('200', 'success');

        }
        return $this->returnJson('11001', '请求失败');


    }

}