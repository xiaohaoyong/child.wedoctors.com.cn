<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_user".
 *
 * @property string $id
 * @property string $childid
 * @property string $touserid
 * @property string $artid
 * @property string $createtime
 * @property string $userid
 * @property string $level
 */
class ArticleUser extends \yii\db\ActiveRecord
{
    public static $levelText=[0=>'未查看',2=>'已查看'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['childid', 'touserid', 'artid', 'createtime', 'userid'], 'required'],
            [['childid', 'touserid', 'artid', 'createtime', 'userid'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'childid' => '儿童',
            'touserid' => '家长',
            'artid' => '文章',
            'createtime' => '推送时间',
            'userid' => '医院',
            'level' => '是否查看',
            'child_type' => '儿童年龄',

        ];
    }
    public function getArticle()
    {
        return $this->hasOne(Article::className(),['id'=>'artid']);
    }

    //关联表articleinfo
    public function GetArticleInfo()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    //查询文章信息byid
    public static function GetInfoById($id)
    {
        return ArticleUser::find()->where(['id'=>$id])->one();
    }

    /**
     * 获取年龄段未推送的用户
     * @param $k 儿童年龄段类型
     * @return int|string
     */

    public static function noSendChild($k,int $doctorid,$type='')
    {
//        $mouth = ChildInfo::getChildType($k);
//        $child=ChildInfo::find()
//            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
//            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
//            ->andFilterWhere(['`doctor_parent`.`doctorid`'=>$this->userData['userid']])
//            ->andFilterWhere(['>=', 'birthday', $mouth['firstday']])
//            ->andFilterWhere(['<=', 'birthday', $mouth['lastday']])
//            ->all();
        //已签约的用户
        $doctorParent= DoctorParent::find()->select('parentid')->where(['doctorid'=>$doctorid])->andFilterWhere(['level'=>1])->column();

        $lmount=date('Y-m-01');
        //该类型 本月已发送的儿童
        $articleUser=ArticleUser::find()->select('touserid')
            ->where(['child_type'=>$k])
            //->andFilterWhere(['>','createtime',strtotime($lmount)])
            ->groupBy('childid')
            ->column();

        $users=array_diff($doctorParent,$articleUser);
        if($doctorParent && $users) {
            if($type=='day'){
                $mouth = ChildInfo::getChildTypeDay($k);
                $childCount = ChildInfo::find()->where([ 'birthday'=>$mouth])->andFilterWhere(['in', 'userid', array_values($users)])->all();
            }else {
                //获取年龄范围
                $mouth = ChildInfo::getChildType($k);
                $childCount = ChildInfo::find()->where(['>=', 'birthday', $mouth['firstday']])->andFilterWhere(['<=', 'birthday', $mouth['lastday']])->andFilterWhere(['in', 'userid', array_values($users)])->all();
            }
        }
        return $childCount?$childCount:[];
    }


    /**
     * 获取年龄段未推送的用户
     * @param $k 儿童年龄段类型
     * @return int|string
     */

    public static function noSendChildNum($k,$doctorid)
    {
        return count(self::noSendChild($k,$doctorid));
    }
}
