<?php

namespace weixin\controllers;

use callmez\wechat\sdk\MpWechat;
use common\helpers\WechatSendTmp;
use common\models\ArticleLog;
use common\models\ChatRecord;
use common\models\Examination;
use common\models\User;
use weixin\models\UserDoctor;
use Yii;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use common\base\BaseWeixinController;
use weixin\models\ChildInfo;
use weixin\models\UserParent;
use weixin\models\ChildHealthRecord;
use weixin\models\ArticleUser;
use weixin\models\DoctorParent;
use weixin\models\Article;
use weixin\models\UserLogin;

/**
 * Parent controller
 */
class ParentController extends BaseWeixinController
{

    public $enableCsrfValidation = false;
    public function actionChildEx($id){
        $ex=Examination::find()->andFilterWhere(['childid'=>$id])->orderBy('field2 desc,field3 desc')->all();
        foreach($ex as $k=>$v)
        {
            $row['id']=$v->id;
            $row['tizhong']=$v->field70;
            $row['shenchang']=$v->field40;
            $row['touwei']=$v->field80;
            $row['bmi']=$v->field20;
            $row['feipang']=$v->field53;
            $row['fayu']=$v->field35;
            $row['yingyang']=$v->field15;
            $row['title']=$v->field82;
            $row['next']=$v->field52;
            $row['date']=$v->field4;

            $data[]=$row;
        }
        return $this->returnJson('200', '成功',$data);
    }
    public function actionChildExView($id){
        $ex=Examination::findOne($id);
        $exa=new Examination();
        $field=$exa->attributeLabels();
        if($ex) {
            $list = $ex->toArray();
            unset($list['id']);
            unset($list['childid']);
            unset($list['source']);

            foreach ($list as $k => $v){
                $row['name']=$field[$k];
                $row['value']=$v;
                $data[$k]=$row;
            }
        }
        return $this->returnJson('200', '成功',$data);
    }
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        throw new BadRequestHttpException('孩子，你是不是走丢了！');
    }


    /**
     * 完善信息/添加宝宝/修改宝宝
     * @author slx
     * 2017-09-25
     */
    public function actionUpdateParent()
    {
        $userid = $this->userData['userid'];
        $data = Yii::$app->request->post();
        if (empty($data['ChildInfo']) || empty($data['UserParent'])) {
            return $this->returnJson('11001', '宝宝（父亲或母亲）姓名限制2~5字符');
        }
        $childid = !empty($data['id']) ? $data['id'] : 0;
        $ChildInfoData = $data['ChildInfo'];

        $child = ChildInfo::findOne($childid);

        $child=$child?$child:new ChildInfo();
        $child->name=$ChildInfoData['name'];
        $child->birthday=strtotime($ChildInfoData['birthday']);
        $child->userid=$userid;
        $child->gender=$ChildInfoData['gender'];
        $child->save();

        $UserParentData = $data['UserParent'];
        $userParent=UserParent::findOne(['userid'=>$userid]);
        $userParent=$userParent?$userParent:new UserParent();
        $userParent->userid=$userid;
        $userParent->mother=$UserParentData['mother'];
        $userParent->father=$UserParentData['fater'];
        $userParent->father_phone=$UserParentData['father_phone'];
        $userParent->mother_phone=$UserParentData['mother_phone'];
        $userParent->save();

        return $this->returnJson('200', '提交成功');
    }

    public function actionChilds($id, $type = 'list')
    {
        if ($type == 'list') {
            $result = ChildInfo::GetList($id);
            return $this->returnJson('200', 'success', $result);
        } else {
            $result = ChildInfo::GetList($id);
            return $this->returnJson('200', 'success', ['count' => count($result)]);
        }
    }

    /**
     * 宝宝列表
     * @author slx
     * 2017-09-25
     */
    public function actionChildList()
    {
        $userid = $this->userData['userid'];
        $result = ChildInfo::GetList($userid);

        $chat = ChatRecord::find()->where(['touserid' => $userid, 'read' => 0])->one();
        $message = $chat ? 1 : 0;
        return $this->returnJson('200', 'success', $result, ['message' => $message]);
    }

    /**
     * 宝宝详情
     * @author slx
     * 2017-09-26
     */
    public function actionChildInfo($id)
    {
        $ChildInfo = ChildInfo::findById($id);
        //宣教记录
        $ArticleData = ArticleUser::GetListByChildid($id, 1, 3);
        //健康记录
        $RecordData = ChildHealthRecord::GetListByChildid($id, 1, 10);
        return $this->returnJson('200', 'success', array('Child' => $ChildInfo, 'Article' => $ArticleData, 'Record' => $RecordData));
    }

    /**
     * 宣教记录全部
     * @author slx
     * 2017-09-26
     */
    public function actionArticleUser($id, $page = 1)
    {
        //宣教记录
        $ArticleData = ArticleUser::GetListByChildid($id, $page, 10);
        return $this->returnJson('200', 'success', $ArticleData);
    }

    /**
     * 健康记录下拉加载
     * @author slx
     * 2017-09-26
     */
    public function actionHealthRecord($id, $page = 1)
    {
        //宣教记录
        $RecordData = ChildHealthRecord::GetListByChildid($id, $page, 10);
        return $this->returnJson('200', 'success', $RecordData);
    }

    /**
     * 添加宣教记录
     * @author slx
     * 2017-09-26
     */
    public function actionAddRecord()
    {
        $userid = $this->userData['userid'];
        $content = Yii::$app->request->post('content');
        $childid = Yii::$app->request->post('childid');
        if (empty($content) || empty($childid)) {
            return $this->returnJson('11001', '记录内容不能为空');
        }
        if (mb_strlen($content, 'utf8') > 200) {
            return $this->returnJson('11001', '最多输入200字，已超过200字');
        }
        $data = ['ChildHealthRecord' => ['childid' => $childid, 'createtime' => time(), 'userid' => $userid, 'content' => $content,],];
        $RecordData = ChildHealthRecord::addData($data);
        if ($RecordData) {
            return $this->returnJson('200', '添加成功');
        }
        return $this->returnJson('11001', '添加失败');
    }

    /**
     * 添加儿保顾问
     * @author slx
     * 2017-09-26
     */
    public function actionAddDoctor($doctor_id)
    {
        $userid = $this->userData['userid'];
        //判断是否签约过
        $DoctorData = DoctorParent::findByParentid($userid);
        if (!empty($DoctorData)) {
            return $this->returnJson('200', 'success', $DoctorData);
        }
        $data = ['DoctorParent' => ['doctorid' => $doctor_id, 'parentid' => $userid, 'createtime' => time(), 'level' => 0,],];
        $result = DoctorParent::addData($data);
        if (!$result) {
            return $this->returnJson('11001', '提交失败');
        } else {
            $user = UserParent::findOne($userid);
            //微信模板消息
            $data = ['first' => array('value' => "您有一条新的签约申请\n"), 'keyword1' => ARRAY('value' => date('Y-m-d H:i')), 'keyword2' => ARRAY('value' => $user->father), 'remark' => ARRAY('value' => "\n点击去通过签约申请！", 'color' => '#221d95'),];
            $touser = UserLogin::findOne(['userid' => $doctor_id])->openid;
            $url = Url::to(\Yii::$app->params['site_url'].'#/docters-user?p=2');

            WechatSendTmp::send($data, $touser, \Yii::$app->params['tongzhi'], $url);
        }
        $DoctorData = DoctorParent::findByParentid($userid);
        return $this->returnJson('200', 'success', $DoctorData);
    }

    /**
     * 儿保顾问
     * @author slx
     * 2017-09-26
     */
    public function actionDoctorParent()
    {
        $userid = $this->userData['userid'];
        $DoctorData = DoctorParent::findByParentid($userid, '');
        return $this->returnJson('200', 'success', $DoctorData);
    }

    /**
     * 家长课堂--文章分类
     * @author slx
     * 2017-09-26
     */
    public function actionArticleCate()
    {
        //文章列表
        $data = \common\models\Article::$catText;
        $data[0] = '推荐';
        return $this->returnJson('200', 'success', $data);
    }

    /**
     * 家长课堂--文章列表
     * @author slx
     * 2017-09-26
     */
    public function actionArticleList($page = 1, $catid = null)
    {
        $user = User::findOne($this->userData['userid']);
        //文章列表
        $data = Article::getList($page, $catid, $user->type);
        return $this->returnJson('200', 'success', $data);
    }

    /**
     * 家长课堂--文章详情
     * @author slx
     * 2017-09-26
     */
    public function actionArticleInfo($id)
    {
        $data = Article::findById($id);
        $data['usertype'] = $this->userData['type'];

        if (!ArticleLog::findOne(['userid' => $this->userData['userid'], 'artid' => $id])) {
            $article_log = new ArticleLog();
            $article_log->userid = $this->userData['userid'];
            $article_log->artid = $id;
            $article_log->save();
        }
        if ($article_user = ArticleUser::findOne(['touserid' => $this->userData['userid'], 'artid' => $id])) {
            $article_user->level = 2;
            $article_user->save();
        }
        return $this->returnJson('200', 'success', $data);
    }

    /**
     * 退出登录
     * @author slx
     * 2017-09-25
     */
    public function actionLogout()
    {
        $userid = $this->userData['userid'];
        $data = UserLogin::updateData($userid, array('openid' => ''));
        if ($data) {
            return $this->returnJson('200', '退出登录成功');
        }
        return $this->returnJson('11001', '退出登录失败');
    }

    /**
     * 扫码回调页面
     * @author slx
     * 2017-09-27
     */
    public function actionScanCode()
    {
        $userid = $this->userData['userid'];
        $data = Yii::$app->request->post();
        return $this->returnJson('200', 'success', $data);
    }

    /**
     * 修改密码
     * @author slx
     * 2017-09-27
     */
    public function actionUpdatePassword()
    {
        $userid = $this->userData['userid'];
        $oldpassword = Yii::$app->request->post('oldpassword');
        $newpassword = Yii::$app->request->post('newpassword');
        $conpassword = Yii::$app->request->post('conpassword');
        if (!preg_match("/^.{8,18}$/", $oldpassword) || !preg_match("/^.{8,18}$/", $newpassword) || !preg_match("/^.{8,18}$/", $conpassword)) {
            return $this->returnJson('11001', ' 密码限制8~18字符');
        }
        if ($oldpassword == $newpassword) {
            return $this->returnJson('11001', '原密码与新密码相同');
        }
        if ($conpassword != $newpassword) {
            return $this->returnJson('11001', '新密码与确认密码不同');
        }
        $UserData = UserLogin::findByUserid($userid);
        $pass = md5(md5($oldpassword.\Yii::$app->params['passwordKey']));
        if ($UserData->password != $pass) {
            return $this->returnJson('11001', ' 原密码不正确');
        }
        $UserLoginData = UserLogin::updateData($userid, array('password' => $newpassword));
        if ($UserLoginData) {
            //注销cookie和清空openid
            //Yii::$app->response->cookies->remove(new \yii\web\Cookie(['name' => 'openid',]));
            $userid = $this->userData['userid'];
            UserLogin::updateData($userid, array('openid' => ''));
            return $this->returnJson('200', '修改成功');
        }
        return $this->returnJson('200', '修改失败');
    }

}
