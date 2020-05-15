<?php

namespace common\models;

use callmez\wechat\sdk\MpWechat;
use common\models\Hospital;
use common\models\ChildHealthRecord;
use common\models\ArticleUser;
use common\models\DoctorParent;
use common\models\ChildInfo;
use common\models\ArticleInfo;

/**
 * This is the model class for table "user_doctor".
 *
 * @property string $userid
 * @property string $name
 * @property integer $sex
 * @property integer $age
 * @property string $birthday
 * @property string $phone
 * @property string $hospitalid
 * @property string $subject_b
 * @property string $subject_s
 * @property integer $title
 * @property string $intro
 * @property string $avatar
 * @property string $skilful
 * @property string $idnum
 * @property integer $province
 * @property string $county
 * @property integer $city
 * @property integer $atitle
 * @property string $otype
 * @property string $authimg
 * @property string $appoint_intro
 * @property string $clinic_hours
 */
class UserDoctor extends \yii\db\ActiveRecord
{

    public static $sexText = [1 => '男', 2 => '女',];
    public static $titleText = [0 => '未设置', 1 => '主任医师', 2 => '副主任医师', 3 => '主治医师', 4 => '住院医师', 16 => '助理医师', 5 => '护士', 6 => '护师', 7 => '主管护师', 8 => '副主任护师', 9 => '主任护师', 10 => '药士', 11 => '药师', 12 => '主管药师', 13 => '副主任药师', 14 => '主任药师', 15 => '其他', 17 => '检验士', 18 => '主管检验师', 19 => '副主任检验师', 20 => '主任检验师'];

    public $appoints;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_doctor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid'], 'required'],
            [['longitude', 'latitude'], 'number'],
            [['appoints','userid', 'sex', 'age', 'birthday', 'phone', 'hospitalid', 'subject_b', 'subject_s', 'title', 'province', 'county', 'city', 'atitle', 'otype', 'appoint'], 'integer'],
            [['name'], 'string', 'max' => 25],
            [['intro', 'avatar', 'skilful','appoint_intro'], 'string', 'max' => 150],
            [['idnum'], 'string', 'max' => 18],
            [['authimg'], 'string', 'max' => 200],
            [['clinic_hours'], 'string', 'max' => 100],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ['appoint_intro'=>'预约介绍(社区选择列表页面)','longitude' => '经度', 'latitude' => '纬度', 'appoints' => '预约项目', 'appoint' => '是否开通预约', 'userid' => '用户ID', 'name' => '姓名', 'sex' => '性别', 'age' => '年龄', 'birthday' => '生日', 'phone' => '医院电话', 'hospitalid' => '所以在医院', 'subject_b' => '一级科室', 'subject_s' => '二级科室', 'title' => '职称', 'intro' => '简介', 'avatar' => '头像', 'skilful' => '简介', 'idnum' => '身份证号码', 'province' => '省', 'county' => '县', 'city' => '市', 'atitle' => '行政职称', 'otype' => '职业类型', 'authimg' => '证件照',];
    }

    /**
     * 获取医生个人信息 刘方露
     */
    public static function GetOneById($id)
    {
        return UserDoctor::find()->where(['userid' => $id])->one();
    }

    /**
     * 关联查询查询对应的医院信息 刘方露
     */
    public function GetHospital()
    {
        return $this->hasOne(Hospital::className(), ['id' => 'hospitalid']);
    }

    /**
     * 医生签约儿童数 刘方露
     */
    public static function GetChildNum($doctorid)
    {
        $sum = 0;
        $model = DoctorParent::find()->select('parentid')->where(['doctorid' => $doctorid, 'level' => 1])->all();
        if ($model) {
            foreach ($model as $value) {
                $sum += ChildInfo::GetSumChild($value);
            }
        }
        return $sum;
    }

    //医生宣教总数 刘方露
    public static function GetArticleNum($doctorid)
    {
        $sum = 0;
        $sum = ArticleUser::find()->where(['userid' => $doctorid])->count('artid');
        return $sum;
    }

    //获取签约儿童列表 刘方露
    public static function GetChildList($dcotorid, $type)
    {
        $array = [];
        $time = time();
        //先获取签约家长的id
        $model = DoctorParent::find()->where(['doctorid' => $dcotorid, 'level' => $type])->all();
        if ($model) {
            foreach ($model as $value) {
                $model_child = ChildInfo::GetInfoByParentid($value['parentid']);
                foreach ($model_child as $child_value) {
                    //儿童名字
                    $data['name'] = $child_value['name'];
                    //儿童年龄
                    $data['age'] = UserDoctor::GetTimeDiff($child_value['birthday']);
                    //儿童手机
                    $data['phone'] = UserDoctor::GetChildPhone($value['parentid']);
                    //儿童id
                    $data['childid'] = $child_value['id'];
                    $data['type'] = $type;
                    $array[] = $data;
                }
            }
        }
        return $array;
    }

    //获取儿童电话 刘方露
    public static function GetChildPhone($parentid)
    {
        $model = user::GetInfoById($parentid);
        return $model['phone'];
    }

    //计算时间差 刘方露
    public static function GetTimeDiff($time)
    {
        $time_diff = time() - $time;
        //年份
        $year = intval($time_diff / 31536000);
        $time_next = $time_diff % 31536000;
        //月份
        $month = intval($time_next / 2592000);
        $time_next = $time_next % 2592000;
        //天份
        $day = intval($time_next / 86400);
        return $year . '岁' . $month . '个月' . $day . '天';
    }

    //获取儿童宣讲列表最近两条 刘方露
    public static function GetTeachListTwo($childid)
    {
        $array = [];
        $model = ArticleUser::find()->orderBy('createtime DESC')->limit(2)->where(['childid' => $childid])->all();
        if ($model) {
            foreach ($model as $value) {
                //宣教标题
                $data['title'] = ArticleInfo::GetInfoById($value['artid'])['title'];
                //宣教时间
                $data['time'] = date('m/d', $value['createtime']);
                $array[] = $data;
            }
        }
        return $array;
    }

    /**
     * 获取儿童健康列表/宣讲列表
     * @param children 儿童childid
     * @param type 查询类型
     * @return 列表数组
     */
    public static function GetHeaandteaList($childid, $type, $page)
    {
        //数据开始条数和结束条数
        $begin = 10 * ($page - 1) + 1;
        $end = 10 * ($page);
        $array = [];
        if ($type == 'tea') {
            $model = ArticleUser::find()->orderBy('createtime DESC')->limit($begin, 10)->where(['childid' => $childid])->all();
            if ($model) {
                foreach ($model as $value) {
                    $data['time'] = date('m/d', $value['createtime']);
                    $data['title'] = ArticleInfo::GetInfoById($value['artid'])['title'];
                    $data['artid'] = $value['artid'];
                    $array[] = $data;
                }
            }
        } elseif ($type == 'hea') {
            $model = ChildHealthRecord::find()->orderBy('createtime DESC')->limit($begin, 10)->where(['childid' => $childid])->all();
            if ($model) {
                foreach ($model as $value) {
                    $data['time'] = date('y/m/d', $value['createtime']);
                    $data['content'] = $value['content'];
                    $data['title'] = UserDoctor::GetOneById($value['userid'])['name'] . '医生记录';
                    $data['artid'] = $value['id'];
                    $array[] = $data;
                }
            }
        }
        return $array;
    }

    /**
     * 获取儿童健康列表/宣讲列表
     * @param children 儿童childid
     * @param type 查询类型
     * @return 列表数组
     */
    public static function GetChildListByAge($num, $age, $doctorid)
    {
        $child_type = $num;

        //获取年龄范围
        $mouth = ChildInfo::getChildType($child_type);

        //已签约的用户
        $doctorParent = DoctorParent::find()->select('parentid')->where(['doctorid' => $doctorid])->column();


        $child = ChildInfo::find()->where(['>', 'birthday', $mouth['firstday']])
            ->andFilterWhere(['<', 'birthday', $mouth['lastday']])
            ->andFilterWhere(['in', 'userid', array_values($doctorParent)])
            ->all();


        if ($child) {

            foreach ($child as $k => $v) {
                //儿童名字
                $data['name'] = $v->name;
                //儿童年龄
                $data['age'] = UserDoctor::GetTimeDiff($v->birthday);
                //儿童手机
                $data['phone'] = \weixin\models\User::findOne(['phone' => $v->userid])->phone;
                //儿童id
                $data['childid'] = $v->id;
                $array[] = $data;
            }
        }
        return $array;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => "userid"]);
    }

    public function getParent()
    {
        return $this->hasMany(UserParent::className(), ['userid' => 'parentid'])->viaTable('doctor_parent', ['doctorid' => 'userid']);
    }

    public function getParents()
    {
        return $this->hasMany(DoctorParent::className(), ['doctorid' => 'userid']);
    }

    public function getChild()
    {
        return $this->hasMany(ChildInfo::className(), ['userid' => 'parentid'])->viaTable('doctor_parent', ['doctorid' => 'userid']);

    }


    public function afterSave($insert, $changedAttributes)
    {

        if ($insert || !$this->qrcode) {
            $qrcodeid=Qrcodeid::findOne(['mappingid'=>$this->userid]);
            if(!$qrcodeid){
                $qrcodeid=new Qrcodeid();
                $qrcodeid->mappingid=$this->userid;
                $qrcodeid->save();
            }

            $data = ['action_name' => "QR_LIMIT_SCENE", 'action_info' => ['scene' => ['scene_id' => $qrcodeid->qrcodeid]]];
            $wechat = new MpWechat([
                'token' => \Yii::$app->params['WeToken'],
                'appId' => \Yii::$app->params['AppID'],
                'appSecret' => \Yii::$app->params['AppSecret'],
                'encodingAesKey' => \Yii::$app->params['encodingAesKey']
            ]);
            $return = $wechat->createQrCode($data);
            if (is_array($return)) {
                $this->qrcode = $return['url'];
                $this->save();

            }
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
}
