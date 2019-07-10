<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/4/18
 * Time: 上午11:41
 */

namespace docapi\modules\v1\controllers;


use docapi\controllers\Controller;
use common\components\Code;
use common\models\AskChatRoom;
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
                $row['type']=1;
                $row['money']=$goods['goods_price'];
                $discount[]=$row;
            }
            $order=Order::createOrder($this->userid,$type_name[$type],$goods,$discount);
            if($order){
                //使用限免名额
                FreeQuota::unset_user_quota($this->userid);
            }
            return $order->orderid;
        }
        return new Code(10010,'成功');
    }
    public function actionView($type='',$goodsid='',$orderid=""){

    }
    public function actionPay($type='',$goodsid='',$orderid=""){

        if(!$orderid) {
            //创建订单
            $goods = OrderGoods::createGoods($type, $goodsid);
            $discount = [];
            $order = Order::createOrder($this->userid, $type, $goods, $discount);

            if($order) {
                //创建微信支付统一下单
                $pay = Factory::payment(\Yii::$app->params['wxpay']);
                $result = $pay->order->unify([
                    'body' => $goods['name'],
                    'out_trade_no' => $order->orderid,
                    'total_fee' => $goods['goods_price'] * 100,
                    'notify_url' => 'https://ask.child.wedoctors.com.cn/notify/ask', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                    'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
                    'openid' => $this->userLogin->aopenid,
                ]);
                $order->prepay_id=$result['prepay_id'];
                $order->save();
            }else{
                return new Code(20010,'失败');
            }

        }else{
            $order = Order::findOne(['orderid'=>$orderid]);
        }
        $time = time();
        $params['appId']=\Yii::$app->params['wxpay']['app_id'];
        $params['timeStamp']=(string)$time;
        $params['nonceStr']=uniqid();
        $params['package']="prepay_id=".$order->prepay_id;
        $params['signType']='MD5';
        key($params);
        // $params['key']= \Yii::$app->params['wxpay']['key'];
        $sign=Support\generate_sign($params, \Yii::$app->params['wxpay']['key']);

        return ['timeStamp'=>$params['timeStamp'],'nonceStr'=>$params['nonceStr'],'package'=>$params['package'],'paySign'=>$sign,'orderid'=>$order->orderid];
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
                $room=AskChatRoom::findOne(['orderid'=>$v->id]);
                $row['roomid']=$room->id;
                $list[]=$row;
            }
        }


        return ['list'=>$list,'page'=>$page->pageCount];
    }
    //实时变更订单状态
    public function actionNotify($orderid){
        $order= Order::findOne(['orderid'=>$orderid]);

        $pay = Factory::payment(\Yii::$app->params['wxpay']);
        $message=$pay->order->queryByOutTradeNumber($order->orderid);
        if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
            // 用户是否支付成功
                if ($message['result_code'] === 'SUCCESS' && $order->status==0) {
                $order->pay_time = time(); // 更新支付时间为当前时间
                $order->pay_method = 1;
                $order->status = 1;
                // 用户支付失败
            } elseif ($message['result_code'] === 'FAIL') {
                $order->status = 0;
            }
        }
        $order->save();

    }

    public function actionScore($orderid,$scores){
        $order=Order::find()->where(['orderid'=>$orderid])->andWhere(['userid'=>$this->userid])->one();
        if($order){
            $order->scores=$scores;
            $order->save();
        }else{
            return new Code(20010,'订单不存在');
        }
    }
}