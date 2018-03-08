<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2017/2/20
 * Time: 19:24
 */

namespace app\components\helper;


class HuanxinChatroomsHelper extends HuanxinHelper
{
    /**
     * @创建聊天室
     * @param array data 参数
     * {
    "name":"testchatroom", //聊天室名称，此属性为必须的
    "description":"server create chatroom", //聊天室描述，此属性为必须的
    "maxusers":300, //聊天室成员最大数（包括群主），值为数值类型，默认值200，最大值5000，此属性为可选的
    "owner":"jma1", //聊天室的管理员，此属性为必须的
    "members":["jma2","jma3"] //聊天室成员，此属性为可选的，但是如果加了此项，数组元素至少一个（注：群主jma1不需要写入到members里面）
    }
     * @return array
     */
    public static function createChatrooms ($data)
    {
        $data=json_encode($data);
        $retval = self::execute('chatrooms','post',$data);
        return $retval['data'];
    }

    /**
     * @删除聊天室
     * @param int $chatroom_id 聊天室ID
     * @return array
     */
    public static function deleteChatrooms ($chatroom_id)
    {
        $retval = self::execute('chatrooms/'.$chatroom_id,'delete');
        return $retval['data'];
    }

    /**
     * @添加聊天室成员[单个]
     * @param int $chatroom_id 聊天室ID
     * @return array
     */
    public static function entersChatrooms ($chatroom_id,$username)
    {
        $retval = self::execute('chatrooms/'.$chatroom_id."/users/".$username,'post');
        return $retval['data'];
    }

    /**
     * @添加聊天室成员[单个]
     * @param int $chatroom_id 聊天室ID
     * @return array
     */
    public static function leaveChatrooms ($chatroom_id,$username)
    {
        $retval = self::execute('chatrooms/'.$chatroom_id."/users/".$username,'delete');
        return $retval['data'];
    }
}