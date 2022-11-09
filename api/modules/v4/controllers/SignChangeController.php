<?php
/**
 * Created by PhpStorm.
 * User: zhangzhenyu
 * Date: 2022/11/8
 * Time: 上午11:30
 */

namespace api\modules\v4\controllers;
use api\controllers\Controller;
use api\models\ChildInfo;
use common\components\Code;
use common\models\Area;
use common\models\Hospital;
use common\models\Pregnancy;
use common\models\SigningRecord;
use yii\web\UploadedFile;

class SignChangeController extends Controller
{

    /**
     * 获取家庭成员
     * @return Code
     */
    public function actionFamilys()
    {
        $pinfo =  Pregnancy::find()->where(['familyid'=>$this->userid])->asArray()->all();
        $cinfo = ChildInfo::find()->where(['userid'=>$this->userid])->asArray()->all();

        $familys = [];
        foreach ($pinfo as $v)
        {
            $sign_info = SigningRecord::get_now_sign($v['familyid']);
            $familys[] = [
                'type'=>'1',
                'id'=>$v['id'],
                'name'=>$v['field1'],
                'sign_item_name'=>$sign_info['name']
            ];
        }

        foreach ($cinfo as $v)
        {
            $sign_info = SigningRecord::get_now_sign($v['userid']);
            $familys[] = [
                'type'=>'2',
                'id'=>$v['id'],
                'name'=>$v['name'],
                'sign_item_name'=>$sign_info['name']
            ];
        }

        return $familys;
    }

    /**
     * 获取区县地区信息
     * @return mixed
     */
    public function actionArea()
    {
        $data =  Area::$county[11];
        $result = [];
        foreach ($data as $k=>$v)
        {
            $result[] = [
                'id'=>$k,
                'name'=>$v
            ];
        }
        return $result;
    }

    /**
     * 获取指定区县的社区团队
     */
    public function actionGetItem()
    {
        $cid = \Yii::$app->request->get('cid');
        return Hospital::find()->select('id,name')->where(['county'=>$cid])->asArray()->all();
    }


    /**
     * 图片上传
     */
    public function actionImg()
    {
        $imagesFile = UploadedFile::getInstancesByName('file');
        if($imagesFile) {
            $upload= new \common\components\UploadForm();
            $upload->imageFiles = $imagesFile;
            $image = $upload->upload();
            if ($image)
                return $image[0];
        }

        return new Code(20000,'failed');

    }

    /**
     * 提交变更申请
     */
    public function actionSubmit()
    {
        $fid = \Yii::$app->request->get('fid');
        $type = \Yii::$app->request->get('type');
        $iid =  \Yii::$app->request->get('iid');

        $imgs =  \Yii::$app->request->get('imgs');
        $imgs = json_decode($imgs,true);
        if (!$imgs || count($imgs) < 1 || count($imgs) >3)
        {
            return new Code(20000,'Imgs count must be Between 1 and 3');
        }
        if (!$fid || !$type || !$iid)
        {
            return new Code(20000,'fid / type / iid can not be empty.');
        }

        $name = '';
        $userid = '';

        if ($type == 1) {
            $pinfo = Pregnancy::find()->where(['id' => $fid])->asArray()->one();
            $name = $pinfo['field1'];
            $userid = $pinfo['familyid'];
        }
        if ($type == 2){
            $cinfo = ChildInfo::find()->where(['id'=>$fid])->asArray()->one();
            $name = $cinfo['name'];
            $userid = $cinfo['userid'];
        }

        $nowsign = SigningRecord::get_now_sign($userid);

        $model = new SigningRecord();
        $model->userid = $fid;
        $model->name = $name;
        $model->type = $type;
        $model->sign_item_id_from = $nowsign['doctorid'];
        $model->sign_item_id_to = $iid;
        $model->info_pics = json_encode($imgs);
        $model->createtime = time();

        if ($model->save(false))
            return true;
        else
            return new Code(20000,'failed');
    }

    /**
     * 签约历史
     */
    public function actionHistory()
    {
        $fid = \Yii::$app->request->get('fid');
        $type = \Yii::$app->request->get('type');
        if ($type == 1) {
            $pinfo = Pregnancy::find()->where(['id' => $fid])->asArray()->one();
            $userid = $pinfo['familyid'];
        }
        if ($type == 2){
            $cinfo = ChildInfo::find()->where(['id'=>$fid])->asArray()->one();
            $userid = $cinfo['userid'];
        }
        $nowsign = SigningRecord::get_now_sign($userid);
        $now['name'] = $nowsign['name'];
        $now['createtime'] = date('Y-m-d H:i:s',$nowsign['createtime']);

        $history = SigningRecord::find()->select('sign_item_id_to')->where(['userid'=>$fid,'status'=>1])->asArray()->all();
        $historys = [];
        foreach ($history as $v)
        {
            $data = Hospital::find()->select('name,createtime')->where(['id'=>$v['sign_item_id_to']])->asArray()->one();
            $data['createtime'] = date('Y-m-d H:i:s',$data['createtime']);
            $historys[] = $data;
        }

        return [
            'now'=>$now,
            'history'=>$historys,
        ];

    }

    /**
     * 签约状态查询
     */
    public function actionStatus()
    {
        $id = \Yii::$app->request->get('id');
        $sign_info = SigningRecord::find()->where(['id' => $id])->asArray()->one();
        $frominfo = Hospital::find()->where(['id' => $sign_info['sign_item_id_from']])->asArray()->one();
        $toinfo = Hospital::find()->where(['id' => $sign_info['sign_item_id_to']])->asArray()->one();
        if ($sign_info) {
            return [
                'status'=>$sign_info['status'],
                'name'=>$sign_info['name'],
                'from_name'=>$frominfo['name'],
                'to_name'=>$toinfo['name']
            ];
        }

        return new Code(20000,'failed');

    }

}