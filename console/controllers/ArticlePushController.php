<?php
namespace console\controllers;
use common\models\ArticlePushVaccine;
use yii\console\Controller;

/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/8/15
 * Time: 上午10:58
 */
class ArticlePushController extends Controller
{
    public function actionVaccine($type){
        $today=date('Ymd');
        $todayS=strtotime($today);
        $todayE=strtotime('+1 day',$todayS);
        switch ($type){
            case 1:
                $openids=\common\models\WeOpenid::find()->where(['>=','createtime',$todayS])
                    ->select('openid')
                    ->andWhere(['<','createtime',$todayE])
                    ->groupBy('openid')
                    ->column();
                $aid=1371;
                break;
            case 2:
                $preg=\common\models\Pregnancy::find()->where(['>','field11',strtotime('-28 week')])
                    ->select('familyid')
                    ->groupBy('familyid')
                    ->column();
                $openids=\common\models\UserLogin::find()->select('openid')
                    ->where(['in','userid',$preg])->andWhere(['!=','openid',''])->column();
                $aid=1369;
                break;
            case 3:
                $childs=\common\models\ChildInfo::find()->where(['<','birthday',strtotime('-2 month')])
                    ->andWhere(['>','birthday',strtotime('-3 month')])
                    ->select('userid')
                    ->groupBy('userid')
                    ->column();
                $openids=\common\models\UserLogin::find()->select('openid')
                    ->where(['in','userid',$childs])->andWhere(['!=','openid',''])->column();
                $aid=1370;
                break;

        }
        $title='您好，给您发来一份儿童疫苗接种指导';

        if($openids && $aid){
            $article=\common\models\ArticleInfo::findOne($aid);
            $data = [
                'first' => array('value' => "{$title}\n"),
                'keyword1' => ARRAY('value' => date('Y年m月d H:i')),
                'keyword2' => ARRAY('value' => '儿宝宝'),
                'keyword3' => ARRAY('value' => '儿宝宝'),
                'keyword4' => ARRAY('value' => $article->title),
                'keyword5' => ARRAY('value' => "疫苗接种知识指导"),
                'remark' => ARRAY('value' => "\n为了您宝宝健康，请仔细阅读哦。", 'color' => '#221d95'),];
            $url = \Yii::$app->params['site_url'] . "#/mission-read";
            $miniprogram = [
                "appid" => \Yii::$app->params['wxXAppId'],
                "pagepath" => "pages/article/view/index?id=$aid",
            ];
            foreach($openids as $k=>$v){

                $articlePushVaccine=ArticlePushVaccine::findOne(['openid'=>$v,'aid'=>$aid]);
                if(!$articlePushVaccine || $articlePushVaccine->state!=1) {
                    $pushReturn = \common\helpers\WechatSendTmp::send($data, 'o5ODa0451fMb_sJ1D1T4YhYXDOcg', \Yii::$app->params['zhidao'], $url, $miniprogram);
                    $articlePushVaccine = new ArticlePushVaccine();
                    $articlePushVaccine->aid = $aid;
                    $articlePushVaccine->openid = $v;
                    $articlePushVaccine->state = $pushReturn?1:0;
                    $articlePushVaccine->save();
                }
                exit;
            }
        }
    }



}