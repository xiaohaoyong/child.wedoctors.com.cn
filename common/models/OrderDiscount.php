<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_discount".
 * 订单优惠
 * @property int $id
 * @property int $orderid
 * @property int $type
 * @property double $money
 */
class OrderDiscount extends \yii\db\ActiveRecord
{
    public $typeText=[
        1=>'成长币兑换',
        2=>'vip折扣',
        3=>'限免',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_discount';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orderid', 'type'], 'integer'],
            [['money'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderid' => '订单ID',
            'type' => '优惠类型',
            'money' => '优惠价格',
        ];
    }
    public static function createDiscount($type)
    {
    }
}