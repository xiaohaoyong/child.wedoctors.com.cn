<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/4/28
 * Time: 上午11:12
 */

namespace ask\modules\v1\controllers;


use ask\controllers\Controller;
use common\models\AskChatRecord;
use common\models\AskChatRoom;
use common\models\Order;
use common\models\Question;

class MessageController extends Controller
{
    public function actionView($orderid){
        $order=Order::findOne(['orderid'=>$orderid]);

        $ques=Question::orderRow($order->id);
        $room=AskChatRoom::findOne(['orderid'=>$ques['ques']->orderid]);
        $chatRecord=AskChatRecord::find()
            ->select('ask_chat_record.*,ask_chat_record_view.content')
            ->leftJoin('ask_chat_record_view', '`ask_chat_record`.`id` = `ask_chat_record_view`.`record_id`')
            ->where(['ask_chat_record.rid'=>$room->id])->limit(200)->asArray()->all();
        AskChatRecord::updateAll(['is_read'=>1],['rid'=>$room->id]);
        $chats=[];
        foreach($chatRecord as $k=>$v){
            if($chats[$v['createtime']])
            {
                $chats[$v['createtime']]['content']=$chats[$v['createtime']]['content'].$v['content'];
            }else{
                $chats[$v['createtime']]=$v;
            }
        }
        $this->foo($chats);
        return ['ques'=>$ques,'room'=>$room->id,'record'=>$chats,'order'=>$order];
    }

    function foo(&$ar) {
        if(! is_array($ar)) return;
        foreach($ar as $k=>&$v) {
            if(is_array($v)) $this->foo($v);
            if($k == 'children') $v = array_values($v);
        }
    }
}