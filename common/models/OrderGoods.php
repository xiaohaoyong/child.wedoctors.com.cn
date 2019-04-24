<?php

namespace common\models;

use Yii;
use yii\base\InvalidConfigException;

/**
 * This is the model class for table "order_goods".
 * 订单商品
 * @property int $id
 * @property int $orderid
 * @property int $goodsid
 * @property int $type
 * @property double $goods_price
 */
class OrderGoods extends \yii\db\ActiveRecord
{
    public static $typeText=[
        1=>'团队',
        2=>'专家',
        3=>'vip服务卡'
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goodsid', 'type','orderid'], 'integer'],
            [['goods_price'], 'number'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goodsid' => '商品ID',
            'type' => '类型（vip卡，专家，儿宝团队)',
            'goods_price'=>'商品价格',
            'orderid'=>'关联订单'
        ];
    }
    //创建订单商品
    public static function createGoods($type,$goodsid=0){
        if(self::$typeText[$type]){
            switch ($type){
                case 1:
                    $goods['id']=0;
                    $goods['goods_price']=0.01;
                    $goods['name']='儿宝宝问医生团队咨询';
                    $goods['type']=$type;
                    break;
                case 2:
                    $goods['id']=$goodsid;
                    $goods['goods_price']=100;
                    $goods['name']='儿宝宝问医生专家咨询';
                    $goods['type']=$type;
                    break;
                case 3:
                    $goods['id']=0;
                    $goods['goods_price']=100;
                    $goods['name']='儿宝宝问医生vip服务卡';
                    $goods['type']=$type;
                    break;
            }
            return $goods;
        }
        throw new InvalidConfigException('商品类型不存在');
    }
}
