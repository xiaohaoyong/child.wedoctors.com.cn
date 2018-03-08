<?php
namespace app\components\helper;
use Yii;


class HuanxinHelper{

    /**
     * @todo 执行请求操作
     * @param string $path 请求的路径
     * @param string $method 请求的方式（get post delete put)
     * @param string $data 请求的数据内容
     * @param return array
     */
    protected static function execute($path, $method, $data = '') {
        $curl = new HttpRequest( Yii::$app->params['hx_url']. $path, true, 2);
        if ($path != 'token') {
            $data = $curl->setHeader('Authorization', 'Bearer ' . self::getToken())->setData($data)->$method();
        } else {
            $data = $curl->setData($data)->$method();
        }
        return json_decode($data, true);
    }

    /**
     * 
     * @todo 获取环信accessToken
     * @return string
     */
    protected static function getToken() {
        $key = "hx_token";
        $redis = \Yii::$app->rdmp;
        $content = $redis->get($key);
        $accessToken = json_decode($content, true);
        if (!$accessToken || (time() - $accessToken['time'] > $accessToken['expires_in'] - 10)) {

            $data = '{"grant_type": "client_credentials","client_id": "' . Yii::$app->params['hx_id'] . '","client_secret": "' . Yii::$app->params['hx_secret'] . '"}';
            $accessToken = self::execute('token', 'post', $data);
            $accessToken['time'] = time();

            $content = json_encode($accessToken);
            $redis->set($key, $content);
        }
        return $accessToken['access_token'];
    }

    /**
     * @todo  获取环信用户
     * @param string $username
     * @return array
     */
    public static function getUser($username) {
        return self::execute('users/' . $username, 'get');
    }

    /**
     * @todo 注册环信用户
     * @param string $uname 用户名
     * @param string $upass 用户密码
     * @param string $unick 用户昵称
     * @return array
     */
    public static function addUser($uname, $upass, $unick = '') {
        $data = '{"username":"' . $uname . '","password":"' . $upass . '", "nickname":"' . $unick . '"}';
        return self::execute('users', 'post', $data);
    }

    /**
     * @todo 删除环信用户
     * @param string $username
     * @return array
     */
    public static function delUser($username) {
        return self::execute('users/' . $username, 'delete');
    }

    /**
     * @todo 增加环信用户好友关系
     * @param string $uname 用户名
     * @param string $fname 即将添加好友的用户名
     * @return array
     */
    public static function addRelation($uname, $fname) {
        return self::execute('users/' . $uname . '/contacts/users/' . $fname, 'post');
    }

    /**
     * @todo 判断环信用户是否在线
     * @param string $uanme 环信用户名
     * @return boolean true在线 false 离线
     */
    public static function userStatus($uname) {
        $retval = self::execute('users/' . $uname . '/status', 'get');
        return $retval['data'][$uname] == 'online';
    }

    /**
     * @todo 删除环信用户好友关系
     * @param string $uname 用户名
     * @param string $fname 即将删除好友的用户名
     * @return array
     */
    public static function delRelation($uname, $fname) {
        return self::execute('users/' . $uname . '/contacts/users/' . $fname, 'delete');
    }

    /**
     * @todo 获取环信用户好友关系
     * @param string $uname 用户名
     * @return array
     */
    public static function getRelation($uname) {
        $data = self::execute('users/' . $uname . '/contacts/users/', 'get');
        return isset($data['data']) ? $data['data'] : array();
    }

    /**
     * @todo 给环信好友发送普通文本消息
     * @param string $from 发送人环信用户名
     * @param string|array 接收人环信用户名，一个或多个
     * @param string $msg  发送的消息内容
     * @param array $ext 自定义消息 
     * @return array 
     */
    public static function setTxtMessage($from, $to, $msg, $ext = array()) {
        $data = array();
        $data['target_type'] = 'users';
        $data['target'] = is_array($to) ? $to : array($to);
        $data['msg'] = array('type' => 'txt', 'msg' => $msg);
        $data['from'] = $from;
        $data['ext'] = $ext ? $ext : new stdClass();
        $message = json_encode($data);
        return self::execute('messages', 'post', $message);
    }

    /**
     * @todo 给环信好友发送图片消息
     * @param string $from 发送人环信用户名
     * @param string|array 接收人环信用户名，一个或多个
     * @param string $url 图片url
     * @param string $secret 上传图片后的secret返回值
     * @param int  $width 图片宽度
     * @param int $height 图片高度
     * @param array $ext 自定义消息
     * @return array 
     */
    public static function setImgMessage($from, $to, $url, $secret = '', $width = 100, $height = 100, $ext = array()) {
        $data = array();
        $data['target_type'] = 'users';
        $data['target'] = is_array($to) ? $to : array($to);
        $data['msg'] = array(
            'type' => 'img',
            'url' => $url,
            'filename' => basename($url),
            'secret' => $secret,
            'size' => array('width' => $width, 'height' => $height),
        );
        $data['from'] = $from;
        $data['ext'] = $ext;
        $message = json_encode($data);
        return self::execute('messages', 'post', $message);
    }

    /**
     * @todo 给环信好友发透传消息
     * @param string $from 发送人环信用户名
     * @param string|array 接收人环信用户名，一个或多个
     * @param array $usermsg 用户自定义消息内容
     * @param array $ext 自定义消息 
     * @return array 
     */
    public static function setCmdMessage($from, $to, $usermsg = array(), $ext = array()) {
        $data = array();
        $data['target_type'] = 'users';
        $data['target'] = is_array($to) ? $to : array($to);
        $data['msg'] = array_merge(array('type' => 'cmd'), $usermsg);
        $data['from'] = $from;
        $data['ext'] = $ext;
        $message = json_encode($data);
        return self::execute('messages', 'post', $message);
    }
    /**
     * @todo 强制用户下线
     * @param string $from 发送人环信用户名
     * @param string|array 接收人环信用户名，一个或多个
     * @param array $usermsg 用户自定义消息内容
     * @param array $ext 自定义消息
     * @return array
     */
    public static function disconnect($uname) {
        return self::execute('users/' . $uname . '/disconnect','get');
    }
    /**
     * @todo 用户禁用
     * @param string $from 发送人环信用户名
     * @param string|array 接收人环信用户名，一个或多个
     * @param array $usermsg 用户自定义消息内容
     * @param array $ext 自定义消息
     * @return array
     */
    public static function deactivate($uname) {
        return self::execute('users/' . $uname . '/deactivate','post');
    }
    /**
     * @todo 用户解禁用
     * @param string $from 发送人环信用户名
     * @param string|array 接收人环信用户名，一个或多个
     * @param array $usermsg 用户自定义消息内容
     * @param array $ext 自定义消息
     * @return array
     */
    public static function activate($uname) {
        return self::execute('users/' . $uname . '/activate','post');
    }
}
