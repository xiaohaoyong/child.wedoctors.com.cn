<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order".
 * 订单主表
 * @property int $id
 * @property int $orderid
 * @property int $createtime
 * @property int $type
 * @property int $pay_method
 * @property double $total
 * @property int $userid
 * @property int $pay_time
 * @property double $orig
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orderid', 'createtime', 'type', 'pay_method', 'userid', 'pay_time'], 'integer'],
            [['total', 'orig'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderid' => '订单号',
            'createtime' => '创建时间',
            'type' => '订单类型',
            'pay_method' => '支付方式',
            'total' => '总额',
            'userid' => '购买用户',
            'pay_time' => '支付时间',
            'orig' => 'Orig',
        ];
    }
}
