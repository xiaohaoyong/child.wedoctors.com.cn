<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 17/10/17
 * Time: 上午10:22
 */

namespace weixin\controllers;

use Codeception\Lib\Interfaces\ActiveRecord;
use common\base\BaseWeixinController;
use common\helpers\WechatSendTmp;
use common\models\ChildInfo;
use common\models\Notice;
use weixin\models\Article;
use weixin\models\ArticleUser;
use weixin\models\DoctorParent;
use weixin\models\UserDoctor;
use weixin\models\UserLogin;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class ArticleController extends BaseWeixinController
{
    public $enableCsrfValidation = false;

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex() {
        throw new BadRequestHttpException('孩子，你是不是走丢了！');
    }

    /**
     * 医生选择文章列表
     * @param int $page
     * @param null $type
     * @return string
     */
    public function actionTypeList($page = 1, $type = null)
    {

        $results['list']=[];
        $results['list1']=[];
        $list=Article::find()->where(['child_type'=>$type,'type'=>1])->all();
        foreach($list as $k=>$v)
        {
            $results['list'][] = Article::findById($v->id);
        }
        $list=Article::find()->where(['child_type'=>$type,'type'=>0])->orderBy('id desc')->limit(10)->all();
        foreach($list as $k=>$v)
        {
            $results['list1'][] = Article::findById($v->id);
        }

        $typename=Article::$childText[$type];
        $results['child_type']=$typename;
        $results['unsend']=$this->noSendChildNum($type);
        return $this->returnJson('200', 'success', $results);
    }

    /**
     * 用户未查看文章列表
     * @param int $page
     * @param null $type
     * @return string
     */
    public function actionList($page = 1, $type = null)
    {
        $article=ArticleUser::find()->where(['touserid'=>$this->userData['userid']]);

        if($type==1) {
            $article->andFilterWhere(['level'=>0]);
        }else{
            $article->andFilterWhere(['level'=>2]);
        }
         $article->orderBy('createtime desc');
        //总共多少页
        $results['countPages'] = ceil($article->count() / 10);
        if ($page > $results['countPages']) {
            $results['list']=[];
            $results['list1']=[];
            return $this->returnJson('200', 'success', $results);
        }
        $pages = new Pagination(['totalCount' => $article->count(), 'pageSize' => 10]);
        $datas = $article->offset($pages->offset)->limit($pages->limit)->all();
        $results['list']=[];
        $results['list1']=[];

        if (!empty($datas)) {
            foreach ($datas as $key => $val) {
                $results['list'][$key] = Article::findById($val->artid);
            }
        }
        return $this->returnJson('200', 'success', $results);

    }


    /**
     * 用户发送文章记录
     * @return string
     */
    public function actionSend()
    {
        $data=\Yii::$app->request->post();
        $list=$data['list'];
        $child_type=$data['child_type'];

        //获取年龄范围
        $mouth= ChildInfo::getChildType($child_type);

        //已签约的用户
        $doctorParent= DoctorParent::find()->select('parentid')->where(['doctorid'=>$this->userData['userid']])->column();


        $child=$this->noSendChild($child_type);

        if($child) {
            $typename=Article::$childText[$child_type];
            $doctor = UserDoctor::findOne($this->userData['userid']);


            foreach ($child as $k => $v) {
                $lmount=date('Y-m-01');
                $articleUser=ArticleUser::find()->where(['childid'=>$v->id,'child_type'=>$child_type])
                    ->andFilterWhere(['>','createtime',strtotime($lmount)])->one();
                if(!$articleUser) {
                    //微信模板消息
                    $data = ['first' => array('value' => "您好！医生给您发来了一份{$typename}儿童中医健康指导。\n"), 'keyword1' => ARRAY('value' => date('Y年m月d H:i')), 'keyword2' => ARRAY('value' => $doctor->hospital->name), 'keyword3' => ARRAY('value' => $doctor->name), 'keyword4' => ARRAY('value' => $v->name), 'keyword5' => ARRAY('value' => "{$typename}儿童中医健康知识"), 'remark' => ARRAY('value' => "\n为了您宝宝健康，请仔细阅读哦。", 'color' => '#221d95'),];
                    $touser = UserLogin::findOne(['userid' => $v->userid])->openid;
                    $url = \Yii::$app->params['site_url']."#/mission-read";
                    WechatSendTmp::send($data, $touser, \Yii::$app->params['zhidao'], $url);


                    //小程序首页推送
                    Notice::setList($v->userid,4,[
                        'title'=>"{$typename}儿童中医药健康知识。",
                        'ftitle'=>$doctor->name.'提醒您及时查看',
                        'id'=>'/article/guidance/index?t=0'
                    ]);

                    foreach ($list as $lk => $lv) {
                        $au=ArticleUser::findOne(['touserid'=>$v->userid,'artid'=>$lv]);
                        if(!$au)
                        {
                            $au= new ArticleUser();
                            $au->childid=$v->id;
                            $au->touserid=$v->userid;
                            $au->createtime=time();
                            $au->userid=$this->userData['userid'];
                            $au->artid=$lv;
                            $au->child_type=$child_type;
                            $au->save();
                            unset($au);
                        }

                    }
                }
            }
        }
        return $this->returnJson('200', 'success');

    }


    public function actionChileType()
    {
        $child_type=Article::$childText;

        foreach($child_type as $k=>$v)
        {
            if($k)
            {

                $return[$k]['name']=$v;
                $return[$k]['unSendNum']=$this->noSendChildNum($k);
            }
        }
        return $this->returnJson('200', 'success',$return);

    }


    /**
     * 获取年龄段未推送的用户
     * @param $k 儿童年龄段类型
     * @return int|string
     */

    public function noSendChild($k)
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
        $doctorParent= DoctorParent::find()->select('parentid')->where(['doctorid'=>$this->userData['userid']])->andFilterWhere(['level'=>1])->column();

        $lmount=date('Y-m-01');
        //该类型 本月已发送的儿童
        $articleUser=ArticleUser::find()->select('touserid')
            ->where(['child_type'=>$k,'userid'=>$this->userData['userid']])
            //->andFilterWhere(['>','createtime',strtotime($lmount)])
            ->groupBy('childid')
            ->column();

        $users=array_diff($doctorParent,$articleUser);
        if($doctorParent) {
            //获取年龄范围
            $mouth = ChildInfo::getChildType($k);
            $childCount = ChildInfo::find()->where(['>=', 'birthday', $mouth['firstday']])->andFilterWhere(['<=', 'birthday', $mouth['lastday']])->andFilterWhere(['in', 'userid', array_values($users)])->all();
        }
        return $childCount;
    }
    /**
     * 获取年龄段未推送的用户
     * @param $k 儿童年龄段类型
     * @return int|string
     */

    public function noSendChildNum($k)
    {
        return count($this->noSendChild($k));
    }

}