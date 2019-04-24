<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/4/24
 * Time: 下午4:13
 */

namespace ask\controllers;


use common\models\Order;
use EasyWeChat\Factory;

class NotifyController extends Controller
{
    public function actionAsk()
    {

        $pay = Factory::payment(\Yii::$app->params['wxpay']);
        $response = $pay->handlePaidNotify(function ($message, $fail) {
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单

            $order = Order::findOne(['orderid' => $message['out_trade_no']]);

            if (!$order || $order->status !== 0) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if ($message['result_code'] === 'SUCCESS') {
                    $order->pay_time = time(); // 更新支付时间为当前时间
                    $order->pay_method = 1;
                    $order->status = 1;

                    // 用户支付失败
                } elseif ($message['result_code'] === 'FAIL') {
                    $order->status = 0;
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });

        $response->send(); // return $response;
    }
}