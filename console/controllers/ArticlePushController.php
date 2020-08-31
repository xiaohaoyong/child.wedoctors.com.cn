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
                $title='新上线宝妈';
                $first='您好，在您接种疫苗前，请认真阅读疫苗接种前后注意事项，提前做好相应功课。';
                break;
            case 2:
                $preg=\common\models\Pregnancy::find()->where(['>','field11',strtotime('-28 week')])
                    ->select('familyid')
                    ->groupBy('familyid')
                    ->column();
                $openids=\common\models\UserLogin::find()->select('openid')
                    ->where(['in','userid',$preg])->andWhere(['!=','openid',''])->column();
                $aid=1369;
                $title='孕晚期准妈';
                $first='准妈您好，在您的宝宝出生时需要接种的疫苗，请认真阅读疫苗接种前注意事项，喜迎宝宝出生。';
                break;
            case 3:
                $childs=\common\models\ChildInfo::find()->where(['<','birthday',strtotime('-1 month')])
                    ->andWhere(['>','birthday',strtotime('-3 month')])
                    ->select('userid')
                    ->groupBy('userid')
                    ->column();
                $openids=\common\models\UserLogin::find()->select('openid')
                    ->where(['in','userid',$childs])->andWhere(['!=','openid',''])->column();
                $aid=1370;
                $title='一，二月龄宝宝家长';
                $first='宝妈您好，您的宝宝已经到达了接种脊灰疫苗的月龄，请认真阅读脊灰疫苗接种前注意事项，选择自己宝宝适合的接种方式。';
                break;

        }

        if($openids && $aid){
            $article=\common\models\ArticleInfo::findOne($aid);
            $data = [
                'first' => array('value' => $first),
                'keyword1' => ARRAY('value' => date('Y年m月d H:i')),
                'keyword2' => ARRAY('value' => '儿宝宝'),
                'keyword3' => ARRAY('value' => '儿宝宝'),
                'keyword4' => ARRAY('value' => $title),
                'keyword5' => ARRAY('value' => $article->title),
                'remark' => ARRAY('value' => "为了您宝宝健康，请仔细阅读哦。", 'color' => '#221d95'),];
            $url = \Yii::$app->params['site_url'] . "#/mission-read";
            $miniprogram = [
                "appid" => \Yii::$app->params['wxXAppId'],
                "pagepath" => "pages/article/view/index?id=$aid",
            ];
            foreach($openids as $k=>$v){

                $articlePushVaccine=ArticlePushVaccine::findOne(['openid'=>$v,'aid'=>$aid]);
                if(!$articlePushVaccine || $articlePushVaccine->state!=1) {
                    $pushReturn = \common\helpers\WechatSendTmp::send($data, $v, \Yii::$app->params['zhidao'], $url, $miniprogram);
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