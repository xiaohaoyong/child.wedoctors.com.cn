<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_goods".
 * 订单商品
 * @property int $id
 * @property int $goodsid
 * @property int $type
 */
class OrderGoods extends \yii\db\ActiveRecord
{
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
            [['goodsid', 'type'], 'integer'],
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
        ];
    }
}
