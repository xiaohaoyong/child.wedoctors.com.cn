<?php
/**
 * Created by PhpStorm.
 * User: xywy
 * Date: 2022/10/24
 * Time: 13:47
 */

namespace common\models;

use Yii;

class MsgComment extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'msg_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'aid'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'createtime' => '推送时间',
            'aid' => '预约订单ID',
            'userid' => '用户ID',
        ];
    }
}