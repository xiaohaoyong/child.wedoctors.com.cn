<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 17/10/9
 * Time: 上午9:37
 */

namespace weixin\controllers;


use callmez\wechat\sdk\MpWechat;
use common\models\ChatRecord;
use common\models\WeOpenid;
use weixin\models\ChildInfo;
use weixin\models\DoctorParent;
use weixin\models\UserParent;
use yii\base\Action;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionMessage()
    {
/*        $openid=13;
        $xml['EventKey']=13;

        //扫码记录
        $weOpenid=WeOpenid::findOne(['openid'=>$openid,'doctorid'=>$xml['EventKey']]);
        $weOpenid=$weOpenid?$weOpenid:new WeOpenid();
        $weOpenid->openid=$openid;
        $weOpenid->doctorid=$xml['EventKey'];
        $weOpenid->createtime=time();
        $weOpenid->save();
        exit;*/

        $array=[5];
        foreach($array as $k=>$v) {

            $data = ['action_name' => "QR_LIMIT_SCENE", 'action_info' => ['scene' => ['scene_id' => $v]]];
            $wechat = new MpWechat([
                'token' => \Yii::$app->params['WeToken'],
                'appId' => \Yii::$app->params['AppID'],
                'appSecret' => \Yii::$app->params['AppSecret'],
                'encodingAesKey' => \Yii::$app->params['encodingAesKey']
            ]);
            $return = $wechat->createQrCode($data);
            echo $v.",".$return['url']."\n";
        }
exit;


        $data['data'] = [
            'first' => array('value' => "恭喜你，已成功签约儿保顾问。", 'color' => '#999'),
            'keyword1' => ARRAY('value' => "恭喜你，已成功签约儿保顾问。", 'color' => '#999'),
            'keyword2' => ARRAY('value' => "恭喜你，已成功签约儿保顾问。", 'color' => '#999'),
            'keyword3' => ARRAY('value' => date('Y年m月d H:i'), 'color' => '#999'),
            'remark' => ARRAY('value' => "点击查看。", 'color' => '#999'),
        ];
        $data['touser'] = "o5ODa0451fMb_sJ1D1T4YhYXDOcg";
        $data['template_id'] = "KJKgdarbimWIeuVMAYu1VyurMUOuPve48ywc3RT6uxY";
        $data['url'] = \Yii::$app->params['site_url']."#/add-docter";
        $mpWechat = new MpWechat([
            'token' => \Yii::$app->params['WeToken'],
            'appId' => \Yii::$app->params['AppID'],
            'appSecret' => \Yii::$app->params['AppSecret'],
            'encodingAesKey' => \Yii::$app->params['encodingAesKey']
        ]);
        var_dump('qwe');
        return $mpWechat->sendTemplateMessage($data);
    }

    /**
     * 医生管辖家长列表
     */
    public function actionParent()
    {
        $data=[];
        $data=DoctorParent::findAll(['doctorid'=>38]);
        if($data)
        {
            foreach($data as $k=>$v)
            {
                $parent=UserParent::findOne($v->parentid);
                $child=ChildInfo::findOne(['userid'=>$v->parentid])->name;
                if($child) {
                    $row['name'] = $child;
                    $row['id'] = $v->parentid;
                    $return[] = $row;
                }
            }
            return $this->returnJson('200', 'success', $return);
        }
        return $this->returnJson('11001', '暂无签约家长');

    }
    /**
     *  返回json结果
     * 200正确11001错误
     */
    public function returnJson($code = 200, $msg = null, $data = null) {
        $redata['code'] = $code;
        if ($data) {
            $redata['data'] = $data;
        }
        if ($msg) {
            $redata['msg'] = $msg;
        }
        return json_encode($redata);
    }
}