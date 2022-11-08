<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "signing_record".
 *
 * @property int $id
 * @property int $userid 家庭ID
 * @property int $type 成员类型，1-孕妇  2-孩子
 * @property int $sign_item_id_from 原签约团队id
 * @property int $sign_item_id_to 变更后的签约团队id
 * @property int $status 状态 0-未审核 1-审核通过 2-审核不通过
 * @property string $info_pics 资料图片
 * @property string $remark 备注
 * @property int $createtime 创建时间
 * @property int $startDate 创建时间
 * @property int $endDate 创建时间
 */
class SigningRecord extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'signing_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'type', 'sign_item_id_from', 'sign_item_id_to', 'status', 'createtime','operator'], 'integer'],
            [['info_pics'], 'string', 'max' => 500],
            [['remark'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => '家庭成员ID',
            'name' => '成员姓名',
            'type' => '成员类型',
            'sign_item_id_from' => '原签约社区',
            'sign_item_id_to' => '变更后签约社区',
            'status' => '审核状态',
            'info_pics' => '资料图片',
            'remark' => '备注',
            'createtime' => '申请时间',
            'startDate' => '开始时间',
            'endDate' => '截止时间',
            'county' => '地区',
            'operator' => '操作人',
        ];
    }

    public function get_pregnancy_info($pid)
    {
        return Pregnancy::findOne($pid);
    }

    public function get_child_info($cid)
    {
        return ChildInfo::GetChildInfo($cid);
    }

    public function get_mom_info($userid)
    {
        return Pregnancy::find()->where(['familyid'=>$userid])->one();
    }

    public function convert_iid($iid)
    {
        $data = Hospital::findOne($iid);
        return isset($data) ? $data['name'] : '';
    }

    public function convert_oid($oid)
    {

    }
}
