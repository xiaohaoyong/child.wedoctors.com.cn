<?php

namespace weixin\controllers;

use callmez\wechat\sdk\MpWechat;
use common\helpers\WechatSendTmp;
use common\models\ChatRecord;
use dosamigos\qrcode\lib\Enum;
use dosamigos\qrcode\QrCode;
use weixin\models\User;
use weixin\models\UserLogin;
use Yii;
use common\base\BaseWeixinController;
use common\helpers\StringHelper;
use common\models\LoginForm;
use common\models\UserDoctor;
use common\models\UserParent;
use common\models\ChildInfo;
use common\models\ChildHealthRecord;
use common\models\Article;
use common\models\ArticleUser;
use common\models\DoctorParent;
use common\models\Subject;
use yii\data\Pagination;

/**
 * Doctor controller
 */
class DoctorController extends BaseWeixinController {

    public $enableCsrfValidation = false;

    /**
     * 医生主页 刘方露
     * @return 医生主页
     */
    public function actionDoctorIndex() {
        $doctorid = $this->userData['userid'];
        $model = UserDoctor::findOne(['userid'=>$doctorid]); //此处需要修改为用户uid
        if (!empty($model)) {
            //医生头像
            $data['avatar'] = $model->avatar;
            //医生名字
            $data['name'] = $model->name;

            //签约儿童总数
            $data['child_num']=ChildInfo::find()
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])
                ->andFilterWhere(['`child_info`.`doctorid`' => $model->hospitalid])
                ->count();

            //宣教次数
            $data['teach_num'] = UserDoctor::GetArticleNum($doctorid); //此处需要修改为用户uid
            $data['doctorid'] = $doctorid; //此处需要修改为用户uid

            $chatCount=ChatRecord::find()->where(['touserid'=>$doctorid,'read'=>0])->count();

            $data['message']=$chatCount;
            return $this->returnJson('200', 'success', $data);
        } else {
            return $this->returnJson('11001', '查无此人'.$doctorid);
        }
    }

    /**
     * 医生个人信息 刘方露
     * @return 医生个人信息
     */
    public function actionDoctorView() {
        $data = [];
        $doctorid = $this->userData['userid'];
        $model = UserDoctor::GetOneById($doctorid);
        if (!empty($model)) {
            //医生头像
            $data['avatar'] = $model->avatar;
            //医生名字
            $data['name'] = $model->name;
            //医生职称
            $data['title'] = $model->title;
            //一级科室
            $data['subject_bname'] = Subject::$subject["$model->subject_b"];
            //二级科室
            $data['subject_sname'] = Subject::$subject["$model->subject_s"];
            //医生所属医院
            $data['hospital'] = $model->hospital->name;
            //医生擅长领域
            $data['skilful'] = $model->skilful;
            //医生简介
            $data['intro'] = $model->intro;
            $data['phone'] = $model->phone;

            return $this->returnJson('200', 'success', $data);
        } else {
            return $this->returnJson('11001', '查无此人');
        }
    }

    /**
     * Logs in a user.
     * @return mixed
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * 医生管辖儿童 刘方露
     * @param type 1:已签约,0:待签约,-1:未签约
     * @return 儿童列表
     */
    public function actionChildHave($type,$age=0,$page=1) {
        $doctorid = $this->userData['userid'];

        $model = DoctorParent::find()->select('parentid')->where(['doctorid'=>$doctorid]);
        if($type==0)
        {
            $type=-1;
        }
        $model->andFilterWhere(['level'=>$type]);
        $child=ChildInfo::find()->where(['in','userid',$model->column()]);
        if($age) {
            $mouth = ChildInfo::getChildType($age);
            $child->andFilterWhere(['>', 'birthday', $mouth['firstday']])->andFilterWhere(['<', 'birthday', $mouth['lastday']]);
        }
        if($page>ceil($child->count()/20))
        {
            $model=[];
        }else {
            $pages = new Pagination(['totalCount' => $child->count()]);
            $model = $child->offset($pages->offset)->limit($pages->limit)->all();
        }
        if (!$model) {
            if ($type == 0) {
                return $this->returnJson('11001', '暂无待签约儿童');
            } else if ($type == 1) {
                return $this->returnJson('11001', '暂无已签约儿童，家长可以通过微信扫描我的设置-二维码，提交签约申请');
            } else if ($type == -1) {
                return $this->returnJson('11001', '暂无未签约儿童');
            }
        } else {
            foreach ($model as $v) {
                //儿童名字
                $data['name'] = $v->name;
                //儿童年龄
                $data['age'] = UserDoctor::GetTimeDiff($v->birthday);
                //儿童手机
                $data['phone'] = UserDoctor::GetChildPhone($v->userid);
                //儿童id
                $data['childid'] = $v->id;
                $data['type'] = $type;
                $array[] = $data;
            }
            return $this->returnJson('200', 'success', ['list'=>$array,'num'=>$child->count()]);
        }
    }

    /**
     * 医生管辖家长列表
     */
    public function actionParent()
    {
        $doctorid=$this->userData['userid'];
        $data=[];
        //$data=DoctorParent::findAll(['doctorid'=>$doctorid]);
        $data=ChatRecord::find()->where(['touserid'=>$doctorid,'read'=>0])->groupBy('userid')->all();
        if($data)
        {
            foreach($data as $k=>$v)
            {
                $child=ChildInfo::findOne(['userid'=>$v->userid])->name;
                $row['name'] = $child;
                $row['id'] = $v->userid;
                $row['message'] = 1;
                $return[] = $row;
            }
            return $this->returnJson('200', 'success', $return);
        }
        return $this->returnJson('11001', '暂无签约家长');

    }

    /**
     * 签约申请/儿童基本信息 刘方露
     * @param childid 宝宝id
     * @param type 1:已签约,0:待签约,-1:未签约
     * @return 儿童信息
     */
    public function actionChildApply($id, $type) {
        $data = [];
        $model = ChildInfo::GetChildInfo($id);
        if (!empty($model)) {
            $parent_model = UserParent::GetInfoById($model['userid']);
            //签约申请儿童名字
            $data['name'] = $model->name;
            //签约申请儿童年龄
            $data['age'] = UserDoctor::GetTimeDiff($model['createtime']);
            //签约申请儿童父亲名字
            $data['father'] = empty($parent_model->father) ? '' : $parent_model->father;
            //签约申请儿童父亲电话
            $data['father_phone'] = empty($parent_model->father_phone) ? '' : $parent_model->father_phone;
            //签约申请儿童母亲名字
            $data['mother'] = empty($parent_model->mother) ? '' : $parent_model->mother;
            //签约申请儿童母亲电话
            $data['mother_phone'] = empty($parent_model->mother_phone) ? '' : $parent_model->mother_phone;
            //签约申请儿童性别
            $data['sex'] = UserDoctor::$sexText["$model->gender"];
            //签约状态
            $data['type'] = $type;
            return $this->returnJson('200', 'success', $data);
        } else {
            return $this->returnJson('11001', '出现异常,请返回重试');
        }
    }

    /**
     * 儿童健康档案顶部 刘方露
     * @return 儿童信息
     */
    public function actionHealthTop($childid) {
        $data = [];
        $doctorid = $this->userData['userid'];
        $model = ChildInfo::GetChildInfo($childid);
        if (!empty($model)) {
            //儿童childid
            $data['childid'] = $model->id;
            //儿童名字
            $data['name'] = $model->name;
            //儿童年龄
            $data['age'] = UserDoctor::GetTimeDiff($model['createtime']);
            //儿童联系电话
            $data['phone'] = UserDoctor::GetChildPhone($model['userid']);
            //宣教记录(两条)
            $data['teachlist'] = UserDoctor::GetTeachListTwo($childid);
            $data['uid'] = $doctorid; //此处需要修改为用户uid
            return $this->returnJson('200', 'success', $data);
        } else {
            return $this->returnJson('11001', '数据异常,请返回重试');
        }
    }

    /**
     * 儿童健康档案宣讲记录/健康记录分页 刘方露
     * @param type 宣教tea,健康hea
     * @param childid 儿童id
     * @param page 页码
     * @return 儿童数据列表
     */
    public function actionHeaandteaList($childid, $type, $page) {
        $data = [];
        $data = UserDoctor::GetHeaandteaList($childid, $type, $page);
        if (!empty($data)) {
            return $this->returnJson('200', 'success', $data);
        } else {
            return $this->returnJson('11001', '数据异常,请返回重试');
        }
    }

    /**
     * 添加儿童健康记录 刘方露
     * @param $childid 儿童id
     * @return 状态码和信息
     */
    public function actionAddHealth() {
        $childid = Yii::$app->request->post('id');
        $uid = $this->userData['userid'];
        $content = Yii::$app->request->post('content');
        if (empty($content)) {
            return $this->returnJson('11001', '记录内容不能为空');
        } else if (StringHelper::utf8_strlen($content) > 200) {
            $num = StringHelper::utf8_strlen($content) - 200;
            return $this->returnJson('11001', '最多输入200字,已超过' . $num . '字');
        }
        $model = new ChildHealthRecord();
        $model->childid = $childid;
        $model->userid = $uid;
        $model->content = $content;
        $model->createtime = time();
        if ($model->save()) {
            // $data['childid']=$childid;
            // $data['type']='hea';
            // $data['page']=1;
            return $this->returnJson('200', '添加成功');
        } else {
            return $this->returnJson('11001', '提交失败,请重试');
        }
    }

   /**
     * 年龄列表
     * @return 年龄列表
     */
    public function actionAgeList() {
        $data = [];
        $data = Article::$childText;
        return $this->returnJson('200', 'success', $data);
    }

    /**
     * 接收年龄筛选返回数据
     * @return mixed
     */
    public function actionBackchildList($num) {
        $doctorid = $this->userData['userid']; //此处需要修改为用户uid
        $data = [];
        $age = Article::$childText["$num"];
        $data = UserDoctor::GetChildListByAge($num, $age, $doctorid);
        if (empty($data)) {
            return $this->returnJson('11001', '暂无已签约儿童');
        }
        return $this->returnJson('200', 'success', $data);
    }

    /**
     * 宣教发送处理
     * @param childid 宝宝id
     * @param artid 文章id
     * @return 状态信息
     */
    public function actionSendTeach() {
        $childid = Yii::$app->request->post('childid');
        $artid = Yii::$app->request->post('artid'); //文章artid
        if (empty($childid)) {
            return $this->returnJson('11001', '没有选择用户');
        } else {
            foreach ($childid as $value) {
                //宣讲数据入库article_user,article(num+1)
                $model = new ArticleUser();
                $model->childid = $value;
                $model->touserid = ChildInfo::GetChildInfo($value)['userid'];
                $model->artid = $artid;
                $model->createtime = time();
                $model->userid = $this->userData['userid']; //此处需要修改为用户uid

                $model_article = Article::find()->where(['id' => $artid])->one();
                $model_article->num+=1;
                if (!$model->save() || !$model_article->save()) {
                    return $this->returnJson('11001', '发送失败');
                }
                //给家长发送通知
                $parentid = ChildInfo::GetChildInfo($childid)['userid']; //家长parentid
                //发送签约成功消息
                $doctor = UserDoctor::findOne($this->userData['userid']);

                //微信模板消息
                $data = ['first' => array(
                    'value' => "您好！医生给您发来一份新的儿童中医药健康指导\n"),
                    'keyword1' => ARRAY('value' => date('Y年m月d H:i')),
                    'keyword2' => ARRAY('value' => $doctor->hospital->name),
                    'keyword3' => ARRAY('value' => $doctor->name),
                    'keyword4' => ARRAY('value' => ChildInfo::findOne($childid)->name),
                    'keyword5' => ARRAY('value' =>  $model_article->info->title),
                    'remark' => ARRAY('value' => "\n为了您宝宝健康，请仔细阅读哦。", 'color' => '#221d95'),
                    ];
                $touser = UserLogin::findOne(['userid' => $parentid])->openid;
                $url = \Yii::$app->params['site_url']."#/article/".$artid;
                $miniprogram=[
                    "appid"=>\Yii::$app->params['wxXAppId'],
                    "pagepath"=>"pages/article/view/index?id=$artid",
                ];
                WechatSendTmp::send($data, $touser, \Yii::$app->params['zhidao'], $url,$miniprogram);
            }
            return $this->returnJson('200', '发送成功');
        }
    }

    /**
     * 通过申请处理
     * @return mixed
     */
    public function actionAgreeApply() {
        $childid = Yii::$app->request->post('id');
        $parentid = ChildInfo::GetChildInfo($childid)['userid']; //家长parentid
        //申请入库doctor_parent
        $model = DoctorParent::find()->where(['parentid' => $parentid])->one();
        $model->level = 1;
        if ($model->save()) {

            //给家长发送通知


            //发送签约成功消息
            $doctor = UserDoctor::findOne($model->doctorid);
            //微信模板消息

            $data = [
                'first' => array('value' => "恭喜你，已成功签约儿保顾问。\n"),
                'keyword1' => ARRAY('value' => $doctor->name, ),
                'keyword2' => ARRAY('value' => $doctor->hospital->name),
                'keyword3' => ARRAY('value' => date('Y年m月d H:i')),
                'remark' => ARRAY('value' => "\n 点击查看。", 'color' => '#221d95'),
            ];
            $touser = UserLogin::findOne(['userid' => $parentid])->openid;
            $url = \Yii::$app->params['site_url']."#/add-docter";

            WechatSendTmp::send($data, $touser, \Yii::$app->params['chenggong'], $url);
            //$data['uid']=$this->userData['userid'];//此处需要修改为用户uid
            return $this->returnJson('200', '已通过申请');
        } else {
            return $this->returnJson('11001', '点击失败,请重试');
        }
    }
    public function actionQrcode($id)
    {
        $doctor=\weixin\models\UserDoctor::findOne($id);
        echo QrCode::png($doctor->qrcode,false,Enum::QR_ECLEVEL_L,7);

    }

}

