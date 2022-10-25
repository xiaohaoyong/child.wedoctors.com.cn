<?php
/**
 * Created by PhpStorm.
 * User: xywy
 * Date: 2022/10/24
 * Time: 13:47
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "question_comment".
 *
 * @property int $id
 * @property int $createtime 时间
 * @property int $qid
 * @property int $is_js 回复及时满意1不满意2满意
 * @property int $is_jue 是否解决1未解决2已解决
 */
class QuestionComment extends \yii\db\ActiveRecord
{
    //满意度
    public static $satisfiedArr = [
        1=>'不满意',
        2=>'满意'
    ];
//是否解决问题
    public static $solvedArr = [
        1=>'未解决',
        2=>'解决'
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createtime', 'qid', 'is_js', 'is_jue'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'createtime' => '创建时间',
            'qid' => '问题ID',
            'userid' => '用户ID',
            'is_satisfied' => '回复及时性满意',
            'is_solve' => '回复是否解决问题',
        ];
    }
}