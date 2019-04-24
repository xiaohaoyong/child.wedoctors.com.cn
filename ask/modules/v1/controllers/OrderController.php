<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/4/18
 * Time: 上午11:41
 */

namespace ask\modules\v1\controllers;


use ask\controllers\Controller;
use common\components\Code;
use common\models\FreeQuota;
use common\models\Order;
use common\models\OrderGoods;
use common\models\Question;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Support;
use yii\data\Pagination;

class OrderController extends Controller
{
    public function actionTrade($type,$is_quota=0){

        if($is_quota){

            if(FreeQuota::get_unset_user_quota($this->userid)) return new Code(20010,'您已使用过限免');

            $quota=FreeQuota::get();
            if(!$quota){
                return new Code(20010,'今日限免名额已抢完');
            }else{
                FreeQuota::user_quota($this->userid);
            }

            $type_name=[
                'item'=>1,
                'expert'=>2,
                'vip'=>3,
            ];
            $goods=OrderGoods::createGoods($type_name[$type]);
            $discount=[];
            if($quota){
                $discount['type']=1;
                $discount['money']=$goods['goods_price'];
            }
            $orderid=Order::createOrder($this->userid,$type_name[$type],$goods,$discount);
            if($orderid){
                //使用限免名额
                FreeQuota::unset_user_quota($this->userid);
            }
            return ;
        }
        return new Code(10010,'成功');
    }
    public function actionView($type,$goodsid){

    }
    public function actionPay($type,$goodsid){


        //创建订单
        $goods=OrderGoods::createGoods($type,$goodsid);
        $discount=[];
        $orderid=Order::createOrder($this->userid,$type,$goods,$discount);

        //创建微信支付统一下单
        $time=time();
        $pay=Factory::payment(\Yii::$app->params['wxpay']);
        $result = $pay->order->unify([
            'body' => $goods['name'],
            'out_trade_no' => $orderid,
            'total_fee' => $goods['goods_price']*100,
            'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => $this->userLogin->aopenid,
        ]);
        if($result['result_code']=='SUCCESS'){
            $params['appId']=\Yii::$app->params['wxpay']['app_id'];
            $params['timeStamp']=(string)$time;
            $params['nonceStr']=uniqid();
            $params['package']="prepay_id=".$result['prepay_id'];
            $params['signType']='MD5';
            key($params);
           // $params['key']= \Yii::$app->params['wxpay']['key'];
            $sign=Support\generate_sign($params, \Yii::$app->params['wxpay']['key']);

            return ['timeStamp'=>$params['timeStamp'],'nonceStr'=>$params['nonceStr'],'package'=>$params['package'],'paySign'=>$sign,'orderid'=>$orderid];
        }
    }
    public function actionList(){


        $count = Order::find()->where(['userid'=>$this->userid])->count();
        $page = new Pagination(['totalCount' => $count,'pageSize'=>'20']);
        $data = Order::find()
            ->where(['userid'=>$this->userid])
            ->offset($page->offset)
            ->limit($page->limit)
            ->orderBy('id desc')
            ->all();
        $list=[];
        if($data){
            foreach($data  as $k=>$v){
                $row['createtime']=date('Y-m-d',$v->createtime);
                $row['orderid']=$v->orderid;
                $row['status']=$v->status;
                $row['statusText']=Order::$msgText[$v->status];
                $question=Question::findOne(['orderid'=>$v->id]);
                $row['ques']=$question?$question:(object)[];
                $list[]=$row;
            }
        }


        return ['list'=>$list,'page'=>$page->pageCount];
    }
}