<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/8/10
 * Time: 上午11:38
 */

namespace common\models;


use common\helpers\WechatSendTmp;
use yii\base\Model;

class ArticleSend extends \yii\db\ActiveRecord
{
    public $artid;
    public $type;
    public $doctorid;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['artid'], 'integer'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'artid' => '文章id',
        ];
    }
    public function send($source='',$test=false){
        $list=$this->artid;
        $child_type=$this->type;

        $article=\common\models\Article::find()
            ->select('id')
            ->where(['child_type'=>$child_type,'type'=>1])->column();
        if($list) {
            $list = array_merge($article, $list);
        }else{
            $list = $article;
        }
        if($list && $child_type && $this->doctorid) {
            $child = ArticleUser::noSendChild($child_type, $this->doctorid);

            if ($child) {
                $typename = Article::$childText[$child_type];
                $doctor = UserDoctor::findOne($this->doctorid);

                foreach ($child as $k => $v) {
                    $lmount = date('Y-m-01');
                    $articleUser = ArticleUser::find()->where(['childid' => $v->id, 'child_type' => $child_type])
                        ->andFilterWhere(['>', 'createtime', strtotime($lmount)])->one();
                    if (!$articleUser) {
                        //微信模板消息
                        $data = ['first' => array('value' => "您好！医生给您发来了一份{$typename}儿童中医药健康指导。\n"), 'keyword1' => ARRAY('value' => date('Y年m月d H:i')), 'keyword2' => ARRAY('value' => $doctor->hospital->name), 'keyword3' => ARRAY('value' => $doctor->name), 'keyword4' => ARRAY('value' => $v->name), 'keyword5' => ARRAY('value' => "{$typename}儿童中医药健康指导"), 'remark' => ARRAY('value' => "\n为了您宝宝健康，请仔细阅读哦。", 'color' => '#221d95'),];
                        $touser = UserLogin::findOne(['userid' => $v->userid])->openid;
                        $url = \Yii::$app->params['site_url'] . "#/mission-read";
                        $miniprogram = [
                            "appid" => \Yii::$app->params['wxXAppId'],
                            "pagepath" => "pages/article/guidance/index?t=0",
                        ];
                        $log=new \common\components\Log('ArticleSend'.$source);
                        $log->addLog($touser."=".$v->userid);
                        $aids='';
                        if(!$test and $touser) {
                            WechatSendTmp::send($data, $touser, \Yii::$app->params['zhidao'], $url, $miniprogram);
                            //小程序首页推送
                            Notice::setList($v->userid, 4, [
                                'title' => "{$typename}儿童中医药健康指导。",
                                'ftitle' => $doctor->name . '提醒您及时查看',
                                'id' => '/article/guidance/index?t=0'
                            ]);
                            foreach ($list as $lk => $lv) {
                                $au = ArticleUser::findOne(['touserid' => $v->userid, 'artid' => $lv]);
                                if (!$au) {
                                    $au = new ArticleUser();
                                    $au->childid = $v->id;
                                    $au->touserid = $v->userid;
                                    $au->createtime = time();
                                    $au->userid = $this->doctorid;
                                    $au->artid = $lv;
                                    $au->child_type = $child_type;
                                    $au->save();
                                    $aids.=$lv."--";
                                    unset($au);
                                }
                            }
                            $log->addLog($aids);
                        }
                        $log->saveLog();
                    }
                }
            }
        }
    }

    public function sendDay($source='',$test=false)
    {
        $list = $this->artid;
        $child_type = $this->type;

        $article = \common\models\Article::find()
            ->select('id')
            ->where(['child_type' => $child_type, 'type' => 1])->column();
        if ($list) {
            $list = array_merge($article, $list);
        } else {
            $list = $article;
        }

        if ($list && $child_type && $this->doctorid) {

        }
    }

}